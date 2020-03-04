<?php

namespace Backend\Models\MySQL\DAO;

use Backend\Library\Service\Auth\UserInterface;
use Backend\Library\Service\RequestHelperService\RequestHelper;
use Backend\Library\Service\RequestHelperService\TDO\RequestFields;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Model\Query\BuilderInterface;

class User extends \Backend\Models\MySQL\Models\User
    implements UserInterface
{

    const STATUS_BID = 0;
    const STATUS_BID_CANCEL = 2;
    const STATUS_USER = 1;
    const STATUS_USER_BLOCKED = 3;

    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    const ROLE_LABEL_MAP = [
        self::ROLE_ADMIN => 'admin',
        self::ROLE_USER => 'user'
    ];

    public function getRoleLabel()
    {
        return self::ROLE_LABEL_MAP[$this->role] ?? 'none';
    }

    public function getImageWepPath()
    {
        if (empty($this->image)) {
            return '/img/no_image.png';
        }
        $userDir = $this->getDI()->get('config')->dirs->users;
        return "{$userDir}/{$this->id}/{$this->image}";
    }

    public function getUserProps()
    {

        $lastName = $this->getLastName();
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'last_name' => empty($lastName) ? '' : $lastName,
            'role' => $this->getRole(),
            'email' => $this->getEmail(),
            'image' => $this->getImage(),
            'date_create' => $this->getDateCreate(),
        ];
    }

    /**
     * @param $login
     * @return UserInterface|User
     */
    public function findUserByLogin($login)
    {
        /**@var User $user */
        $user = self::findFirst([
            'conditions' => 'email = :email:',
            'bind' => [
                'email' => $login
            ]
        ]);
        return $user;
    }

    public function findUserById($userId)
    {
        if (empty($userId)) {
            return null;
        }
        /**@var User $user */
        $user = self::findFirst($userId);
        if (empty($user)) {
            return null;
        }
        return $user;
    }

    public static function getBuilder()
    {
        return (new self())->getModelsManager()->createBuilder()
            ->columns(['u.*'])
            ->addFrom(self::class, 'u');
    }

    public static function bindFields(BuilderInterface $builder, string $fields): BuilderInterface
    {
        $categoryFields = ['id', 'email', 'role', 'name', 'last_name', 'image', 'phone', 'date_create', 'status'];
        /**@var RequestHelper $requestHelperService */
        $requestHelperService = FactoryDefault::getDefault()->get('requestHelperService');
        $requestFields = (new RequestFields())
            ->setBuilder($builder)
            ->setDefaultFields($categoryFields)
            ->setFields($fields)
            ->setItemFilter(function ($field) {
                return "u.{$field}";
            });
        $requestHelperService->bindFields($requestFields);
        return $builder;
    }



    /**
     * @param BuilderInterface $builder
     * @param string $role
     * @return BuilderInterface
     */
    public static function andRole(BuilderInterface $builder, $role)
    {
        $builder->andWhere('u.role = :role:', [
            'role' => $role
        ]);

        return $builder;
    }

    /**
     * @param BuilderInterface $builder
     * @param array $roleList
     * @return BuilderInterface
     */
    public static function andRoleIn(BuilderInterface $builder, $roleList)
    {
        $builder->andWhere('u.role in ({roleList:array})', [
            'roleList' => $roleList
        ]);

        return $builder;
    }

}
