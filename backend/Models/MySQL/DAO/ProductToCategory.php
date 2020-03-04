<?php


namespace Backend\Models\MySQL\DAO;


use Phalcon\Mvc\Model\Query\BuilderInterface;

class ProductToCategory extends \Backend\Models\MySQL\Models\ProductToCategory
{

    public static function leftJoinProduct(BuilderInterface $builder): BuilderInterface
    {
        $builder->leftJoin(ProductToCategory::class, 'p.id = pToC.product_id', 'pToC');
        return $builder;
    }

    public static function rightJoinProductCategory(BuilderInterface $builder): BuilderInterface
    {
        $builder->rightJoin(ProductToCategory::class, 'pToC.category_id = pc.id', 'pToC');
        return $builder;
    }

    public static function categoryJoinProductToCategoryAndProduct(BuilderInterface $builder): BuilderInterface
    {
        $builder
            ->rightJoin(ProductToCategory::class, 'pToC.category_id = pc.id', 'pToC')
            ->leftJoin(Product::class, 'pToC.product_id = p.id', 'p');

        return $builder;
    }

}