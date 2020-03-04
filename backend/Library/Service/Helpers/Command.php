<?php
namespace Backend\Library\Service\Helpers;

use Phalcon\Mvc\Model\Query\BuilderInterface;

abstract class Command
{
    /**
     * @var BuilderInterface
     */
    protected $builder;

    /**
     * @param BuilderInterface $builder
     * @return $this
     */
    public function setBuilder(BuilderInterface $builder)
    {
        $this->builder = $builder;
        return $this;
    }

    /**
     * @return bool
     */
    public abstract function execute();
}