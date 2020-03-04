<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 12.03.19
 * Time: 21:16
 */

namespace Backend\Library\Service\Auth;


interface UserInterface extends \Phalcon\Mvc\ModelInterface
{
    public function getEmail();

    public function getPassword();

    public function getId();

    public function getRole();
    /**
     * @param $password
     * @return UserInterface
     */
    public function setPassword($password);
    /**
     * @param $login
     * @return UserInterface
     */
    public function findUserByLogin($login);

    /**
     * @param $userId
     * @return UserInterface
     */
    public function findUserById($userId);
}