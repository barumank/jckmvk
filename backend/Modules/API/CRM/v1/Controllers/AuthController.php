<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 02.01.20
 * Time: 11:03
 */

namespace Backend\Modules\API\CRM\v1\Controllers;

use Backend\Models\MySQL\DAO\User;
use Backend\Modules\API\CRM\v1\Controller;
use Backend\Modules\API\CRM\v1\Validations\RegistrationValidation;

class AuthController extends Controller
{
    /**
     * @AclRoles(guest)
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function RegistrationAction()
    {
        if (!$this->request->isPost()) {
            return $this->jsonResponse->sendError('Не верный запрос');
        }
        $post = $this->request->getPost();
        $messages = (new RegistrationValidation())->validate($post);
        if ($messages->count() > 0) {
            return $this->jsonResponse->sendError('Ошибка при регистрации', $messages);
        }
        /**@var User $user */
        $user = (new User())
            ->setEmail($post['email'])
            ->setRole(User::ROLE_USER)
            ->setName($post['name'])
            ->setPassword($this->security->hash($post['password']))
            ->setDateCreate(date('Y-m-d H:i:s'));

        if (!$user->save()) {
            return $this->jsonResponse->sendError('Ошибка сохранения');
        }
        return $this->jsonResponse->sendSuccess();
    }

    /**
     * @AclRoles(guest)
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function LoginAction()
    {
        if (!$this->request->isPost()) {
            return $this->jsonResponse->sendError('Не верный запрос');
        }
        $login = $this->request->getPost('login');
        $password = $this->request->getPost('password');
        if ($this->auth->logIn($login, $password)) {
            /**@var User $user*/
            $user = $this->auth->getUser();
            return $this->jsonResponse->sendSuccess([
                'user' => $user->getUserProps()
            ]);
        }
        return $this->jsonResponse->sendError('Не верный логин или пароль');
    }

    /**
     * @AclRoles(guest)
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function LogoutAction()
    {
        $this->auth->logOut();
        return $this->jsonResponse->sendSuccess();
    }

    /**
     * @AclRoles(guest)
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function ErrorAction()
    {
        return $this->jsonResponse->sendError('Вы не идентифицированы');
    }

    /**
     * @AclRoles(guest)
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function AclErrorAction()
    {
        return $this->jsonResponse->sendError('Не достаточный уровень доступа');
    }
}
