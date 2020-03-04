<?php


namespace Backend\Library\Service\RequestHelperService;


use Backend\Library\Service\RequestHelperService\TDO\RequestFields;
use Phalcon\Mvc\Model\Query\BuilderInterface;

/**
 * Помогает в привязке свойств к сущности
 * Class RequestHelper
 * @package Backend\Library\Service\RequestHelperService
 */
class RequestHelper
{
    public function getEntityFields(string $fields, array $defaultFields, callable $itemFilter)
    {
        if (empty($fields)) {
            return $this->map($defaultFields, $itemFilter);
        }
        $columns = explode(',', $fields);
        if (empty($columns)) {
            return $this->map($defaultFields, $itemFilter);
        }
        $columns = array_flip($columns);
        $bindColumns = [];
        foreach ($defaultFields as $field) {
            if (isset($columns[$field])) {
                $bindColumns[] = $field;
            }
        }
        if (empty($bindColumns)) {
            return $this->map($defaultFields, $itemFilter);
        }
        return $this->map($bindColumns, $itemFilter);
    }

    public function bindFields(RequestFields $requestFields)
    {
        $queryBuilder = $requestFields->getBuilder();
        $fields = $requestFields->getFields();
        $defaultFields = $requestFields->getDefaultFields();
        $itemFilter = $requestFields->getItemFilter();

        $columns = $this->getEntityFields($fields, $defaultFields, $itemFilter);
        $queryColumns = $queryBuilder->getColumns();
        if (is_string($queryColumns)) {
            $queryColumns = explode(',', $queryColumns);
        }
        $queryColumns = array_merge($queryColumns, $columns);
        $queryColumns = array_unique($queryColumns);
        $queryColumns = array_filter($queryColumns, function ($item) {
            return mb_strpos($item, '.*') === false;
        });
        $queryBuilder->columns($queryColumns);
    }

    private function map(array $fields, callable $itemFilter)
    {
        $out = [];
        foreach ($fields as $field) {
            $out[] = $itemFilter($field);
        }
        return $out;
    }
}