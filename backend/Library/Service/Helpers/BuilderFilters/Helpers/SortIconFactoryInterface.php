<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 26.04.19
 * Time: 16:30
 */

namespace Backend\Library\Service\Helpers\BuilderFilters\Helpers;


interface SortIconFactoryInterface
{
    public function getUnsorted();

    public function getUp();

    public function getDown();
}