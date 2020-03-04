<?php

namespace Backend\Models\MySQL\DAO;


use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Model\Query\BuilderInterface;

class AttributeProduct extends \Backend\Models\MySQL\Models\AttributeProduct
{

    public static function getBuilder()
    {
        /**@var \Phalcon\Mvc\Model\ManagerInterface $modelsManager */
        $modelsManager = FactoryDefault::getDefault()->get('modelsManager');
        return $modelsManager->createBuilder()
            ->columns('ap.*')
            ->addFrom(self::class, 'ap');
    }


    /**
     * @param int[] $categoryIdList
     * @return AttributeProduct[]
     */
    public static function findByCategoryIdList(array $categoryIdList)
    {
        if (empty($categoryIdList)) {
            return [];
        }

        return self::getBuilder()
            ->rightJoin(ProductToAttribute::class, 'ap.id = pToA.attribute_id', 'pToA')
            ->leftJoin(ProductToCategory::class, 'pToA.product_id = pToC.product_id', 'pToC')
            ->where('pToC.category_id in ({categoryIdList:array})', [
                'categoryIdList' => $categoryIdList
            ])
            ->groupBy('ap.id')
            ->getQuery()
            ->execute();
    }

    /**
     * @param int $productType
     * @param int $userId
     * @return AttributeProduct[]
     */
    public static function findByProductTypeAndUserId(int $productType, int $userId)
    {

        $builder = self::getBuilder();
        ProductToAttribute::rightJoinProductToAttribute($builder);
        ProductToAttribute::leftJoinProduct($builder);
        return $builder
            ->andWhere('(p.user_id = :userId: or p.type =:type:) and p.type = :requestType:', [
                'type' => ProductCategory::TYPE_ALLOW_ALL,
                'requestType' => $productType,
                'userId' => $userId,
            ])
            ->groupBy('ap.id')
            ->getQuery()
            ->execute();
    }

    /**
     * @param int[] $productIdList
     * @return AttributeProduct[]
     */
    public static function findGroupPropertyByProductIdList(array $productIdList)
    {
        if(empty($productIdList)){
            return[];
        }
        $builder = self::getBuilder();
        ProductToAttribute::rightJoinProductToAttribute($builder);
        $query = $builder->where('pToA.product_id in ({productIdList:array}) and ap.group_id is not null', [
            'productIdList' => $productIdList
        ])->groupBy('ap.id')->getQuery()->execute();
        $out = [];
        foreach ($query as $item){
            /**@var AttributeProduct $item*/
            $out[$item->getId()] = $item;
        }
        return $out;

    }

}