<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 26.04.19
 * Time: 16:32
 */

namespace Backend\Library\Service\Helpers\BuilderFilters\Helpers;


class AdminSortIconFactory implements SortIconFactoryInterface
{
    public function getUnsorted()
    {
        return '<i class="fa fa-unsorted"></i>';
    }

    public function getUp()
    {
        return '<i class="fa fa-sort-up"></i>';
    }

    public function getDown()
    {
        return '<i class="fa fa-sort-desc"></i>';
    }

}