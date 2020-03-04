<?php


namespace Backend\Library\Parsers\SDK\Command;

use Backend\Library\Parsers\SDK\Interfaces\Product as ProductInterface;
use Backend\Library\Phalcon\Db\MysqlAdapter;
use Backend\Library\Phalcon\Logger\TcpLogger;
use Phalcon\Logger\Adapter as LoggerAdapter;

class SaveProducts extends BaseCommand
{
    /** @var TcpLogger */
    private $logger;
    /** @var MysqlAdapter */
    private $db;
    /**@var ProductInterface[] */
    private $products = [];
    /**@var ProductInterface[] */
    private $productHashMap = [];

    public function __construct(MysqlAdapter $db,LoggerAdapter $logger)
    {
        $this->logger = $logger;
        $this->db = $db;
    }

    protected function getSqlExistHashPattern():string
    {
        return 'select id,hash from product where hash in (%s)';
    }

    public function getDb(): MysqlAdapter
    {
        return $this->db;
    }

    /**
     * @param ProductInterface[] $products
     * @return SaveProducts
     */
    public function setProducts(array $products): SaveProducts
    {
        $this->productHashMap = [];
        foreach ($products as $product){
            $this->productHashMap[$product->hash()] = $product;
        }
        $this->products  = array_values($this->productHashMap);
        return $this;
    }

    /**
     * @return ProductInterface[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @return ProductInterface[]
     */
    public function getProductHashMap(): array
    {
        return $this->productHashMap;
    }


    public function save():bool
    {
        $hashes = array_keys($this->productHashMap);
        $this->db->ping();
        $existsHashes = $this->getExistHashes($hashes);
        $this->bindExistsHashes($existsHashes);
        $newProducts = array_diff_key($this->productHashMap,$existsHashes);
        $newProductsChunk = array_chunk($newProducts, 1000);
        foreach ($newProductsChunk as $chunk){
            if(!$this->insert($chunk)){
                return false;
            }
        }
        $hashes = array_keys($newProducts);
        $existsHashes = $this->getExistHashes($hashes);
        $this->bindExistsHashes($existsHashes);
        return true;
    }


    private function bindExistsHashes($existsHashes)
    {
        if (!empty($existsHashes)) {
            foreach ($existsHashes as $hash => $item) {
                /**@var ProductInterface $product */
                $product = $this->productHashMap[$hash];
                $product->setId($item['id']);
            }
        }
    }

    /**
     * @param ProductInterface[] $products
     * @return bool
     */
    private function insert($products)
    {
        if (empty($products)) {
            return true;
        }
        $db = $this->db;
        $insert = [];
        foreach ($products as $product) {
            $userId = $product->getUserId();
            $name = $db->escapeString($product->getName());
            $vendorCode = $db->escapeString($product->getVendorCode());
            $rrc = $product->getRrc();
            $rrc = empty($rrc)? 'null':$rrc;
            $discount = $product->getDiscount();
            $discount = empty($discount)? 'null':$discount;
            $amount = $product->getAmount();
            $imageName = $product->getImageName();
            $imageName = empty($imageName)? 'null':$db->escapeString($imageName);
            $type = $product->getType();
            $hash = $db->escapeString($product->hash());
            $insert[] = "({$userId},{$name},{$vendorCode},{$rrc},{$discount},{$amount},{$imageName},{$type},{$hash})";
        }
        $sql = 'insert into product(user_id,name,vendor_code,rrc,discount,amount,image,type,hash)values' . implode(',' . PHP_EOL, $insert) . ';';
        return $db->execute($sql);
    }


}
