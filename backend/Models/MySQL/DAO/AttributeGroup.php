<?php


namespace Backend\Models\MySQL\DAO;


use Phalcon\Di\FactoryDefault;

class AttributeGroup extends \Backend\Models\MySQL\Models\AttributeGroup
{
    public static function getBuilder()
    {
        /**@var \Phalcon\Mvc\Model\ManagerInterface $modelsManager */
        $modelsManager = FactoryDefault::getDefault()->get('modelsManager');
        return $modelsManager->createBuilder()
            ->columns('ag.*')
            ->addFrom(self::class, 'ag');
    }

    /**
     * @param int[] $groupIdList
     * @return AttributeGroup[]
     */
    public static function findByIdList(array $groupIdList)
    {
       if(empty($groupIdList)){
           return [];
       }
       return self::getBuilder()
            ->where('ag.id in ({groupIdList:array})', [
                'groupIdList' => $groupIdList
            ])
            ->getQuery()
            ->execute();
    }


}