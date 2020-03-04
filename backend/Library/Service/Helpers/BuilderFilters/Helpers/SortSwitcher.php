<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 23.04.19
 * Time: 16:39
 */

namespace Backend\Library\Service\Helpers\BuilderFilters\Helpers;


class SortSwitcher
{
    const SORT_UP = 'up';
    const SORT_DOWN = 'down';

    private $sort = '';

    /**
     * @return string
     */
    public function getSort(): string
    {
        return $this->sort;
    }

    /**
     * @param string $sort
     * @return SortSwitcher
     */
    public function setSort(string $sort): SortSwitcher
    {
        $this->sort = $sort;
        return $this;
    }

    public function nextSort()
    {
        switch ($this->sort){
            case '':
                return self::SORT_UP;
            case self::SORT_UP:
                return self::SORT_DOWN;
            default:
                return '';
        }
    }

    public function getDBSort()
    {
        switch ($this->sort){
            case self::SORT_DOWN:
                return 'DESC';
            case self::SORT_UP:
                return 'ASC';
            default:
                return '';
        }
    }
}