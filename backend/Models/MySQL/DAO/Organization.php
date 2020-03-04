<?php


namespace Backend\Models\MySQL\DAO;


use Phalcon\Di\FactoryDefault;

class Organization extends \Backend\Models\MySQL\Models\Organization
{
    const MIN_IMAGE_WIDTH = 300;
    const MIN_IMAGE_HEIGHT = 300;

    public static function getBuilder()
    {
        /**@var \Phalcon\Mvc\Model\ManagerInterface $modelsManager */
        $modelsManager = FactoryDefault::getDefault()->get('modelsManager');
        return $modelsManager->createBuilder()
            ->columns('o.*')
            ->addFrom(self::class, 'o');
    }

    public static function findRootIdByUserId($userId): ?int
    {
        $query = Organization::getBuilder()->columns('o.id')
            ->where('o.root_id is null and o.user_id = :userId:', ['userId' => $userId])
            ->getQuery()->execute();
        if (empty($query->count())) {
            return null;
        }
        return $query->getFirst()['id'];
    }

    public static function hasExistsUser($organizationId, $userId): bool
    {
        return empty(UserToOrganization::findFirst([
            'conditions' => 'user_id = :userId: and organization_id = :organizationId:',
            'bind' => [
                'userId' => $userId,
                'organizationId' => $organizationId
            ]
        ]));
    }
}