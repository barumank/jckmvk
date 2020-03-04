<?php


namespace Backend\Models\MySQL\DAO;


use Backend\Library\Service\RequestHelperService\RequestHelper;
use Backend\Library\Service\RequestHelperService\TDO\RequestFields;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Model\Query\BuilderInterface;

class Product extends \Backend\Models\MySQL\Models\Product
{
    public static function getBuilder()
    {
        /**@var \Phalcon\Mvc\Model\ManagerInterface $modelsManager */
        $modelsManager = FactoryDefault::getDefault()->get('modelsManager');
        return $modelsManager->createBuilder()
            ->columns('p.*')
            ->addFrom(self::class, 'p');
    }

    public static function bindFields(BuilderInterface $builder, string $fields): BuilderInterface
    {
        $categoryFields = ['id', 'user_id', 'name','vendor_code','rrc','discount','amount','image','type','hash'];
        /**@var RequestHelper $requestHelperService */
        $requestHelperService = FactoryDefault::getDefault()->get('requestHelperService');
        $requestFields = (new RequestFields())
            ->setBuilder($builder)
            ->setDefaultFields($categoryFields)
            ->setFields($fields)
            ->setItemFilter(function ($field) {
                return "p.{$field}";
            });
        $requestHelperService->bindFields($requestFields);
        return $builder;
    }



}