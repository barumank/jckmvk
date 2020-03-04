<?php

namespace Backend\Library\Parsers\SDK\Command;

use Backend\Library\Parsers\SDK\Interfaces\Category;
use Backend\Library\Parsers\SDK\Interfaces\Category as CategoryInterface;
use Backend\Library\Parsers\SDK\Interfaces\Product as ProductInterface;
use Backend\Library\Phalcon\Db\MysqlAdapter;
use Backend\Library\Phalcon\Logger\TcpLogger;
use Phalcon\Logger\Adapter as LoggerAdapter;

class SaveCategories extends BaseCommand
{
    /** @var TcpLogger */
    private $logger;
    /** @var MysqlAdapter */
    private $db;

    /**@var ProductInterface[] */
    private $products = [];
    /**@var CategoryInterface[][] */
    private $categoryLevelMap = [];
    /**@var CategoryInterface[] */
    private $categoryHashMap;
    /**@var CategoryInterface[][] */
    private $categoryChildrenMap;

    public function __construct(MysqlAdapter $db, LoggerAdapter $logger)
    {
        $this->logger = $logger;
        $this->db = $db;
    }

    protected function getSqlExistHashPattern():string
    {
        return 'select id,parent_id,hash from product_category where hash in (%s)';
    }

    public function getDb(): MysqlAdapter
    {
        return $this->db;
    }

    /**
     * @param ProductInterface[] $products
     * @return SaveCategories
     */
    public function setProducts(array $products): SaveCategories
    {
        $this->products = $products;
        $this->categoryHashMap = [];
        $this->categoryLevelMap = [];
        $this->categoryChildrenMap = [];
        foreach ($this->products as $product) {
            $categories = $product->getCategories();
            foreach ($categories as $category) {
                $this->categoryHashMap[$category->hash()] = $category;
            }
        }
        foreach ($this->products as $product) {
            $categories = $product->getCategories();
            $newCategories = [];
            $next = null;
            /**@var CategoryInterface $next */
            foreach ($categories as $category) {
                $categoryHash = $category->hash();
                if (isset($this->categoryHashMap[$categoryHash])) {
                    $category = $this->categoryHashMap[$categoryHash];
                }
                $parentKey = empty($next) ? '' : $next->hash();
                $this->categoryChildrenMap[$parentKey][$categoryHash] = $category;
                $next = $category;
                $newCategories[] = $category;
            }
            $this->categoryLevelMap[] = $newCategories;
            $product->setCategories($newCategories);
        }
        return $this;
    }

    public function save(): bool
    {
        //получаем категории для пошаговой вставки
        $categorySaveSteps = $this->getCategorySaveSteps();
        $hashes = array_keys($this->categoryHashMap);
        $this->db->ping();
        $existsHashes = $this->getExistHashes($hashes);
        $this->bindExistsHashes($existsHashes);
        foreach ($categorySaveSteps as $categories) {
            $hashes = [];
            $categoryHashMap = [];
            foreach ($categories as $category) {
                $hash = $category->hash();
                if (isset($existsHashes[$hash])) {
                    continue;
                }
                $category = $this->categoryHashMap[$hash];
                $categoryHashMap[$hash] = $category;
                $hashes[] = $hash;
            }
            $categoryHashMapChunk = array_chunk($categoryHashMap, 1000);
            foreach ($categoryHashMapChunk as $chunk) {
                if (!$this->insert($chunk)) {
                    return false;
                }
            }
            $newHashes = $this->getExistHashes($hashes);
            $this->bindExistsHashes($newHashes);
        }
        return true;
    }

    /**
     * @return CategoryInterface[][]
     */
    private function getCategorySaveSteps()
    {
        //собираем категории в пачки для вставки
        $isFinal = false;
        $arrayCondition = [];
        $insertSteps = [];
        while (!$isFinal) {
            $levelInsert = [];
            foreach ($this->categoryLevelMap as $key => &$array) {
                if (isset($arrayCondition[$key]) && empty($arrayCondition[$key])) {
                    continue;
                }
                /**@var CategoryInterface $category */
                $category = current($array);
                $levelInsert[$category->hash()] = $category;
                $arrayCondition[$key] = next($array);
            }
            $insertSteps[] = $levelInsert;
            $isFinal = true;
            foreach ($arrayCondition as $item) {
                if (!empty($item)) {
                    $isFinal = false;
                    break;
                }
            }
        }
        return $insertSteps;
    }

    private function bindExistsHashes($existsHashes)
    {
        if (empty($existsHashes)) {
            return;
        }
        foreach ($existsHashes as $hash => $item) {
            /**@var CategoryInterface $category */
            $category = $this->categoryHashMap[$hash];
            $category->setId($item['id'])->setParentId($item['parent_id']);
            //проставляем родителя у дочерних категорий
            if (isset($this->categoryChildrenMap[$hash])) {
                foreach ($this->categoryChildrenMap[$hash] as $childCategory) {
                    /**@var CategoryInterface $childCategory */
                    $childCategory->setParentId($category->getId());
                }
            }
        }
    }

    /**
     * @param CategoryInterface[] $categories
     * @return bool
     */
    private function insert($categories)
    {
        if (empty($categories)) {
            return true;
        }
        $db = $this->db;
        $insert = [];
        foreach ($categories as $category) {
            $parentId = $category->getParentId();
            $parentId = $parentId === null ? 'null' : $parentId;
            $insert[] = "({$category->getUserId()},{$parentId},{$db->escapeString($category->getName())},{$category->getType()},{$db->escapeString($category->hash())})";
        }
        $sql = 'insert into product_category(user_id,parent_id,name,type,hash)values' . implode(',' . PHP_EOL, $insert) . ';';
        return $db->execute($sql);
    }

}
