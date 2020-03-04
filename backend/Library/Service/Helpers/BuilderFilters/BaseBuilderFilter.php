<?php
namespace Backend\Library\Service\Helpers\BuilderFilters;

use Backend\Library\Service\Helpers\BuilderFilters\Helpers\SortHelper;
use Phalcon\Mvc\Model\Query\BuilderInterface;

abstract class BaseBuilderFilter
{

    /**
     * @var SortHelper
     */
    protected $sortHelper;

    /**
     * BaseBuilderFilter constructor.
     */
    public function __construct()
    {
        $this->sortHelper = new SortHelper();
    }

    /**
     * @return SortHelper
     */
    public function getSortHelper(): SortHelper
    {
        return $this->sortHelper;
    }

    /**
     * @param SortHelper $sortHelper
     * @return BaseBuilderFilter
     */
    public function setSortHelper(SortHelper $sortHelper): BaseBuilderFilter
    {
        $this->sortHelper = $sortHelper;

        return $this;
    }

    public function getSortLink($fieldName)
    {
        return $this->sortHelper->getSortLink($fieldName);
    }

    /**
     * @param BuilderInterface $builder
     */
    public abstract function addConditions($builder);
}