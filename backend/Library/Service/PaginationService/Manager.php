<?php

namespace Backend\Library\Service\PaginationService;

use Phalcon\Mvc\User\Component;

/**
 * Class PaginationService
 *
 * @author  Artem Pasvlovskiy tema23p@gmail.com
 *
 * @package Backend\Library\Service
 */
class Manager extends Component
{

    /**
     * @var string
     */
    private $viewName;

    private $paginate;

    /**
     * @var string
     */
    private $pageName;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var int|null
     */
    private $pageCountName;

    /**
     * @var string
     */
    private $pageCountView;

    /**
     * @var string
     */
    private $sortView;
    /**
     * @var string|null
     */
    private $sortFieldName;
    /**
     * @var string|null
     */
    private $sortTypeName;

    private $appendSortParams = [];

    /**
     * Manager constructor.
     * @param $viewName
     * @param $pageCountView
     * @param $sortView
     */
    public function __construct($viewName, $pageCountView, $sortView)
    {
        $this->viewName = $viewName;
        $this->pageCountView = $pageCountView;
        $this->sortView = $sortView;
    }

    /**
     * @param array $appendSortParams
     * @return Manager
     */
    public function setAppendSortParams(array $appendSortParams): Manager
    {
        $this->appendSortParams = $appendSortParams;
        return $this;
    }

    public function renderSort($field)
    {
        $queryParams = $this->getQueryParams();
        $sortParams = [
            $this->sortFieldName => $queryParams[$this->sortFieldName] ?? '',
            $this->sortTypeName => $queryParams[$this->sortTypeName] ?? 0,
            $this->pageCountName => $this->getPageCount($queryParams)
        ];

        $sortParams = array_merge($sortParams,$this->appendSortParams);

        return $this->view->getPartial($this->sortView, [
            'prefix' => $this->prefix,
            'headerField' => $field,
            'sortField' => $sortParams[$this->sortFieldName],
            'sortType' => $sortParams[$this->sortTypeName],
            'queryParams' => $sortParams,
        ]);
    }

    private function getPageCount($queryParams)
    {

        return $queryParams[$this->pageCountName] ?? 10;
    }

    public function renderPageCount()
    {
        $queryParams = $this->getQueryParams();
        if (isset($queryParams[$this->pageName])) {
            unset($queryParams[$this->pageName]);
        }
        return $this->view->getPartial($this->pageCountView, [
            'prefix' => $this->prefix,
            'selectCount' => $this->getPageCount($queryParams),
            'pageCountName' => $this->pageCountName,
            'queryParams' => $queryParams,
        ]);
    }

    public function getQueryParams()
    {

        $queryParams = $this->request->getQuery();
        unset($queryParams['_url']);
        return $queryParams;
    }

    public function render()
    {

        $queryParams = $this->getQueryParams();
        return $this->view->getPartial($this->viewName, [
            'paginate' => $this->paginate,
            'prefix' => $this->prefix,
            'pageName' => $this->pageName,
            'queryParams' => $queryParams,
        ]);
    }

    /**
     * @return string
     */
    public function getSortView(): string
    {
        return $this->sortView;
    }

    /**
     * @param string $sortView
     * @return Manager
     */
    public function setSortView(string $sortView): Manager
    {
        $this->sortView = $sortView;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSortFieldName()
    {
        return $this->sortFieldName;
    }

    /**
     * @param string|null $sortFieldName
     * @return Manager
     */
    public function setSortFieldName($sortFieldName)
    {
        $this->sortFieldName = $sortFieldName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSortTypeName()
    {
        return $this->sortTypeName;
    }

    /**
     * @param string|null $sortTypeName
     * @return Manager
     */
    public function setSortTypeName($sortTypeName)
    {
        $this->sortTypeName = $sortTypeName;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPageCountName()
    {
        return $this->pageCountName;
    }

    /**
     * @param int|null $pageCountName
     * @return Manager
     */
    public function setPageCountName($pageCountName)
    {
        $this->pageCountName = $pageCountName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPageCountView()
    {
        return $this->pageCountView;
    }

    /**
     * @param string $pageCountView
     * @return Manager
     */
    public function setPageCountView($pageCountView)
    {
        $this->pageCountView = $pageCountView;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getViewName()
    {
        return $this->viewName;
    }

    /**
     * @param mixed $viewName
     *
     * @return Manager
     */
    public function setViewName($viewName)
    {
        $this->viewName = $viewName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaginate()
    {
        return $this->paginate;
    }

    /**
     * @param mixed $paginate
     *
     * @return Manager
     */
    public function setPaginate($paginate)
    {
        $this->paginate = $paginate;

        return $this;
    }

    /**
     * @return string
     */
    public function getPageName()
    {
        return $this->pageName;
    }

    /**
     * @param string $pageName
     *
     * @return Manager
     */
    public function setPageName($pageName)
    {
        $this->pageName = $pageName;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     *
     * @return Manager
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

}