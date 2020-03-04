<?php

namespace Backend\Models\MySQL\DAO;


use Backend\Library\Service\RequestHelperService\RequestHelper;
use Backend\Library\Service\RequestHelperService\TDO\RequestFields;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Model\Query\BuilderInterface;

/**
 * Class ProductCategory
 * @package Backend\Models\MySQL\DAO
 */
class ProductCategory extends \Backend\Models\MySQL\Models\ProductCategory
{
    const MIN_IMAGE_WIDTH = 305;
    const MIN_IMAGE_HEIGHT = 296;

    const TYPE_ALLOW_ALL = 0;
    const TYPE_ALLOW_USER = 1;

    public function beforeValidation()
    {
        $this->hash = hash('sha256', "{$this->name}@{$this->user_id}@{$this->parent_id}");
    }

    public function afterSave()
    {
       $this->clearCache();
    }

    public function clearCache()
    {
        /**@var \Backend\Library\Service\CategoryService\Manager $categoryService */
        $categoryService = FactoryDefault::getDefault()->get('categoryService');
        $categoryService->setUserId($this->user_id);
        switch ((int)$this->type) {
            case self::TYPE_ALLOW_ALL:
                $categoryService->cacheClearBase();
                break;
            case self::TYPE_ALLOW_USER:
                $categoryService->cacheClearCustom();
                break;
        }
    }

    public function typeIsAllowAll()
    {
        return (int)$this->type === self::TYPE_ALLOW_ALL;
    }

    public function typeIsAllowUser()
    {
        return (int)$this->type === self::TYPE_ALLOW_USER;
    }

    public function hasUserId($userId)
    {
        return (int)$this->user_id === (int)$userId;
    }

    public static function getBuilder()
    {
        /**@var \Phalcon\Mvc\Model\ManagerInterface $modelsManager */
        $modelsManager = FactoryDefault::getDefault()->get('modelsManager');
        return $modelsManager->createBuilder()
            ->columns('pc.*')
            ->addFrom(self::class, 'pc');
    }

    public static function bindFields(BuilderInterface $builder, string $fields): BuilderInterface
    {
        $categoryFields = ['id', 'user_id', 'parent_id', 'name', 'image'];
        /**@var RequestHelper $requestHelperService */
        $requestHelperService = FactoryDefault::getDefault()->get('requestHelperService');
        $requestFields = (new RequestFields())
            ->setBuilder($builder)
            ->setDefaultFields($categoryFields)
            ->setFields($fields)
            ->setItemFilter(function ($field) {
                return "pc.{$field}";
            });
        $requestHelperService->bindFields($requestFields);
        return $builder;
    }
}