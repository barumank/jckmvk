<?php


namespace Backend\Library\Service\RequestHelperService\TDO;


use Phalcon\Mvc\Model\Query\BuilderInterface;

class RequestFields
{
    /**
     * @var string
     */
    private $fields;
    /**
     * @var string[]
     */
    private $defaultFields;
    /**
     * @var callable
     */
    private $itemFilter;
    /**
     * @var BuilderInterface
     */
    private $builder;

    /**
     * @return string
     */
    public function getFields(): string
    {
        return $this->fields;
    }

    /**
     * @param string $fields
     * @return RequestFields
     */
    public function setFields(string $fields): RequestFields
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getDefaultFields(): array
    {
        return $this->defaultFields;
    }

    /**
     * @param string[] $defaultFields
     * @return RequestFields
     */
    public function setDefaultFields(array $defaultFields): RequestFields
    {
        $this->defaultFields = $defaultFields;
        return $this;
    }

    /**
     * @return callable
     */
    public function getItemFilter(): callable
    {
        return $this->itemFilter;
    }

    /**
     * @param callable $itemFilter
     * @return RequestFields
     */
    public function setItemFilter(callable $itemFilter): RequestFields
    {
        $this->itemFilter = $itemFilter;
        return $this;
    }

    /**
     * @return BuilderInterface
     */
    public function getBuilder(): BuilderInterface
    {
        return $this->builder;
    }

    /**
     * @param BuilderInterface $builder
     * @return RequestFields
     */
    public function setBuilder(BuilderInterface $builder): RequestFields
    {
        $this->builder = $builder;
        return $this;
    }



}