<?php


namespace Backend\Library\Parsers\SDK\Command;


use Backend\Library\Parsers\SDK\Interfaces\Category as CategoryInterface;
use Backend\Library\Parsers\SDK\TDO\ProductCategory;
use Backend\Library\Phalcon\Db\MysqlAdapter;
use Backend\Library\Phalcon\Logger\TcpLogger;
use Phalcon\Logger\Adapter as LoggerAdapter;
use Backend\Library\Parsers\SDK\Interfaces\Product as ProductInterface;

class SaveProductCategories extends BaseCommand
{
    /** @var TcpLogger */
    private $logger;
    /** @var MysqlAdapter */
    private $db;
    /**@var ProductInterface[] */
    private $products = [];
    /**@var CategoryInterface[] */
    private $hashProductCategoryMap;
    /**@var ProductCategory[] */
    private $productCategoriesHashMap;

    public function __construct(MysqlAdapter $db, LoggerAdapter $logger)
    {
        $this->logger = $logger;
        $this->db = $db;
    }

    protected function getSqlExistHashPattern():string
    {
        return 'select product_id,category_id,hash from product_to_category where hash in (%s)';
    }

    public function getDb(): MysqlAdapter
    {
        return $this->db;
    }

    /**
     * @param ProductInterface[] $products
     * @return SaveProductCategories
     */
    public function setProducts(array $products): SaveProductCategories
    {
        $this->hashProductCategoryMap = [];
        $this->products = $products;
        foreach ($products as $product) {
            $categories = $product->getCategories();
            $category = end($categories);
            $hash = $product->hash();
            if (!empty($category)
                && !isset($this->hashProductCategoryMap[$hash])) {
                $this->hashProductCategoryMap[$hash] = $category;
            }
        }
        return $this;
    }

    public function save(): bool
    {
        $this->initProductToCategory();
        $hashes = array_keys($this->productCategoriesHashMap);
        $this->db->ping();
        $existsHashes = $this->getExistHashes($hashes);
        $newProductCategory = array_diff_key($this->productCategoriesHashMap,$existsHashes);
        $newProductCategoryChunk = array_chunk($newProductCategory, 1000);
        foreach ($newProductCategoryChunk as $chunk){
            if(!$this->insert($chunk)){
                return false;
            }
        }
        return true;
    }

    private function initProductToCategory(): void
    {
        $this->productCategoriesHashMap = [];
        foreach ($this->products as $product) {
            $hash = $product->hash();
            if (!isset($this->hashProductCategoryMap[$hash])) {
                continue;
            }
            $category = $this->hashProductCategoryMap[$hash];
            $productToCategory = new ProductCategory($product->getId(), $category->getId());
            $this->productCategoriesHashMap[$productToCategory->hash()] = $productToCategory;
        }
    }

    /**
     * @param ProductCategory[] $productCategories
     * @return bool
     */
    private function insert($productCategories)
    {
        if (empty($productCategories)) {
            return true;
        }
        $db = $this->db;
        $insert = [];
        foreach ($productCategories as $productToCategory) {
            $productId = $productToCategory->getProductId();
            $categoryId = $productToCategory->getCategoryId();
            $hash = $db->escapeString($productToCategory->hash());
            $insert[] = "({$productId},{$categoryId},{$hash})";
        }
        $sql = 'insert into product_to_category(product_id,category_id,hash)values' . implode(',' . PHP_EOL, $insert) . ';';
        return $db->execute($sql);
    }


}
