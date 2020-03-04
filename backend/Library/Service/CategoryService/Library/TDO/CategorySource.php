<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 12.02.20
 * Time: 0:41
 */

namespace Backend\Library\Service\CategoryService\Library\TDO;


class CategorySource
{
    /**
     * @var int[][]
     */
    private $childrenMap;
    /**
     * @var int[]
     */
    private $parentMap;

    /**
     * @return \int[][]
     */
    public function getChildrenMap(): array
    {
        return $this->childrenMap;
    }

    /**
     * @param \int[][] $childrenMap
     * @return CategorySource
     */
    public function setChildrenMap(array $childrenMap): CategorySource
    {
        $this->childrenMap = $childrenMap;
        return $this;
    }

    /**
     * @return int[]
     */
    public function getParentMap(): array
    {
        return $this->parentMap;
    }

    /**
     * @param int[] $parentMap
     * @return CategorySource
     */
    public function setParentMap(array $parentMap): CategorySource
    {
        $this->parentMap = $parentMap;
        return $this;
    }

    public function hasChildren(int $categoryId)
    {
        return isset($this->childrenMap[$categoryId]);
    }


}
