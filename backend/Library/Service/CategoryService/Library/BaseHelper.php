<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 11.02.20
 * Time: 21:51
 */

namespace Backend\Library\Service\CategoryService\Library;


use Backend\Library\Service\CategoryService\Library\TDO\CategorySource;

abstract class BaseHelper
{

    abstract public function setCategoryId(?int $categoryId): BaseHelper;

    abstract public function getCategoryId(): ?int;

    abstract protected function getCategorySource(): CategorySource;

    abstract public function clearCache(): bool;

    abstract public function hasChildren(int $categoryId): bool;

    public function getParentIdList(): array
    {
        $parentMap = $this->getCategorySource()->getParentMap();
        $parentGenerator = function ($categoryId, $parentMap) {
            $parentId = $categoryId;
            yield $categoryId;
            while (isset($parentMap[$parentId])) {
                $parentId = $parentMap[$parentId];
                if (empty($parentId)) {
                    break;
                }
                yield $parentId;
            }
        };
        $out = [];
        $generator = $parentGenerator($this->getCategoryId(), $parentMap);
        foreach ($generator as $id) {
            $out[] = $id;
        }
        return array_reverse($out);

    }

    public function getChildrenIdList(): array
    {
        $childrenMap = $this->getCategorySource()->getChildrenMap();
        $childrenGenerator = function ($categoryId) use (&$childrenMap, &$childrenGenerator) {
            if (isset($childrenMap[$categoryId])) {
                foreach ($childrenMap[$categoryId] as $id) {
                    if (isset($childrenMap[$id])) {
                        yield from $childrenGenerator($id);
                    }
                    yield $id;
                }
            }
        };
        $out = [];
        $result = $childrenGenerator($this->getCategoryId());
        foreach ($result as $item) {
            $out[] = $item;
        }
        return $out;
    }


}
