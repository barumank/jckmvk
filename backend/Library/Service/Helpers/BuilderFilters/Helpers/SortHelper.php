<?php

namespace Backend\Library\Service\Helpers\BuilderFilters\Helpers;

use Phalcon\Di\FactoryDefault;
use Phalcon\Http\Request;
use Phalcon\Mvc\Url;

class SortHelper
{
    /**
     * @var string
     */
    private $sortField;
    /**
     * @var string
     */
    private $linkPrefix = '';

    private $sortFieldQueryName = '';

    private $sortQueryName = '';

    private $sortSwitcher;

    /**
     * SortHelper constructor.
     */
    public function __construct()
    {
        $this->sortSwitcher = new SortSwitcher();
    }


    /**
     * @return string
     */
    public function getLinkPrefix(): string
    {
        return $this->linkPrefix;
    }

    /**
     * @param string $linkPrefix
     * @return SortHelper
     */
    public function setLinkPrefix(string $linkPrefix): SortHelper
    {
        $this->linkPrefix = $linkPrefix;

        return $this;
    }

    /**
     * @return string
     */
    public function getSortField()
    {
        return $this->sortField;
    }

    /**
     * @param string $sortField
     * @return SortHelper
     */
    public function setSortField($sortField)
    {
        $this->sortField = $sortField;
        return $this;
    }

    /**
     * @return string
     */
    public function getSort()
    {
        return $this->sortSwitcher->getSort();
    }

    /**
     * @param string $sort
     * @return SortHelper
     */
    public function setSort($sort)
    {
        $this->sortSwitcher->setSort($sort);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSortFieldQueryName()
    {
        return $this->sortFieldQueryName;
    }

    /**
     * @param string $sortFieldQueryName
     * @return SortHelper
     */
    public function setSortFieldQueryName($sortFieldQueryName)
    {
        $this->sortFieldQueryName = $sortFieldQueryName;
        return $this;
    }

    /**
     * @return string
     */
    public function getSortQueryName()
    {
        return $this->sortQueryName;
    }

    /**
     * @param string $sortQueryName
     * @return SortHelper
     */
    public function setSortQueryName($sortQueryName)
    {
        $this->sortQueryName = $sortQueryName;
        return $this;
    }

    public function getDBSort()
    {
        return $this->sortSwitcher->getDBSort();
    }


    public function getSortLink($fieldName)
    {

        $di = FactoryDefault::getDefault();

        /**@var Request $request */
        $request = $di->get('request');
        $queryParams = $request->getQuery();
        unset($queryParams['_url']);

        if($di->has('paginationService')){
            /**@var \Backend\Library\Service\PaginationService\Manager $paginationService*/
            $paginationService = $di->get('paginationService');
            $pageName = $paginationService->getPageName();
            if(!empty($pageName)){
                unset($queryParams[$pageName]);
            }
        }

        $fieldQueryName = empty($this->sortFieldQueryName) ? 'field' : $this->sortFieldQueryName;
        $sortQueryName = empty($this->sortQueryName) ? 'sort' : $this->sortQueryName;

        $queryParams[$sortQueryName] = SortSwitcher::SORT_UP;
        if($fieldName == $this->sortField){
            $queryParams[$sortQueryName] = $this->sortSwitcher->nextSort();
        }

        $queryParams[$fieldQueryName] = $fieldName;

        /**@var Url $urlService*/
        $urlService = $di->get('url');

        return $urlService->get($this->linkPrefix,$queryParams);
    }

    public function getSortIcon($fieldName,SortIconFactoryInterface $factory)
    {

        if($fieldName != $this->sortField){
            return $factory->getUnsorted();
        }

        $sort = $this->sortSwitcher->getSort();
        switch ($sort) {
            case SortSwitcher::SORT_UP:
                return $factory->getUp();
                break;
            case SortSwitcher::SORT_DOWN:
                return $factory->getDown();
                break;
            default:
                return $factory->getUnsorted();
        }
    }

}