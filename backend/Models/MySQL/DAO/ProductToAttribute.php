<?php


namespace Backend\Models\MySQL\DAO;


use Backend\Library\Service\RequestHelperService\RequestHelper;
use Backend\Library\Service\RequestHelperService\TDO\RequestFields;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Model\Query\BuilderInterface;

class ProductToAttribute extends \Backend\Models\MySQL\Models\ProductToAttribute
{
    public static function getBuilder()
    {
        /**@var \Phalcon\Mvc\Model\ManagerInterface $modelsManager */
        $modelsManager = FactoryDefault::getDefault()->get('modelsManager');
        return $modelsManager->createBuilder()
            ->columns('pToA.*')
            ->addFrom(self::class, 'pToA');
    }

    public static function leftJoinProduct(BuilderInterface $builder): BuilderInterface
    {
        $builder->leftJoin(Product::class, 'pToA.product_id = p.id', 'p');
        return $builder;
    }
    public static function rightJoinProductToAttribute(BuilderInterface $builder): BuilderInterface
    {
        $builder->rightJoin(self::class, 'pToA.attribute_id = ap.id', 'pToA');
        return $builder;
    }


    /**
     * @param int[] $productIdList
     * @param BuilderInterface|null $builder
     * @return BuilderInterface
     */
    public static function findByProductIdList(array $productIdList, BuilderInterface $builder = null)
    {
        $queryBuilder = $builder;
        if (empty($queryBuilder)) {
            $queryBuilder = self::getBuilder();
        }
        if (empty($productIdList)) {
            return $queryBuilder;
        }
        return $queryBuilder->where('pToA.product_id in ({productIdList:array})', [
            'productIdList' => $productIdList
        ]);
    }

    public static function bindFields(BuilderInterface $builder, string $fields): BuilderInterface
    {
        $categoryFields = ['id', 'attribute_id', 'product_id','value','hash'];
        /**@var RequestHelper $requestHelperService */
        $requestHelperService = FactoryDefault::getDefault()->get('requestHelperService');
        $requestFields = (new RequestFields())
            ->setBuilder($builder)
            ->setDefaultFields($categoryFields)
            ->setFields($fields)
            ->setItemFilter(function ($field) {
                return "pToA.{$field}";
            });
        $requestHelperService->bindFields($requestFields);
        return $builder;
    }
}