<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 11.02.20
 * Time: 21:39
 */

namespace Backend\Library\Service\CategoryService\Library;


use Backend\Library\Phalcon\Db\MysqlAdapter;
use Backend\Library\Service\CategoryService\Library\TDO\CategorySource;
use Backend\Models\MySQL\DAO\ProductCategory;
use Phalcon\Db\AdapterInterface;

class CustomCategoryBaseHelper extends BaseHelper
{
    /**
     * @var int|null
     */
    private $userId;
    /**
     * @var int|null
     */
    private $categoryId;
    /**
     * @var string
     */
    private $cacheDir;
    /**
     * @var CategorySource
     */
    private $categorySource;
    /**
     * @var MysqlAdapter
     */
    private $db;

    public function __construct(string $cacheDir, AdapterInterface $db)
    {
        $this->cacheDir = $cacheDir;
        $this->db = $db;
    }


    public function setUserId(?int $userId): BaseHelper
    {
        $this->userId = $userId;
        $this->categorySource = null;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(?int $categoryId): BaseHelper
    {
        $this->categoryId = $categoryId;
        return $this;
    }

    protected function getCategorySource(): CategorySource
    {

        if (!empty($this->categorySource)) {
            return $this->categorySource;
        }
        $cache = "{$this->cacheDir}/custom{$this->userId}.txt";
        if (file_exists($cache)) {
            $this->categorySource = unserialize(file_get_contents($cache));
            return $this->categorySource;
        }

        $categoryType = ProductCategory::TYPE_ALLOW_USER;
        $sql = "select id,parent_id from product_category where user_id={$this->userId} and `type`={$categoryType};";
        $result = $this->db->fetchAll($sql);
        $parentMap = [];
        $childrenMap = [];
        foreach ($result as $item) {
            $childrenMap[$item['parent_id']][$item['id']] = $item['id'];
            $parentMap[$item['id']] = $item['parent_id'];
        }
        $this->categorySource = (new CategorySource())
            ->setChildrenMap($childrenMap)
            ->setParentMap($parentMap);

        file_put_contents($cache, serialize($this->categorySource));
        return $this->categorySource;
    }

    public function hasChildren(int $categoryId): bool
    {
        return $this->getCategorySource()->hasChildren($categoryId);
    }

    public function clearCache(): bool
    {
        $cache = "{$this->cacheDir}/custom{$this->userId}.txt";
        if (file_exists($cache)) {
            return unlink($cache);
        }
        return true;
    }


}
