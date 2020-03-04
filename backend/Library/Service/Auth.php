<?php

namespace Backend\Library\Service;

use Backend\Library\Service\Auth\CodeVerification;
use Backend\Library\Service\Auth\CRUD;
use Backend\Library\Service\Auth\UserInterface;
use Backend\Models\MySQL\DAO\User;
use Phalcon\Events\Manager;
use Phalcon\Mvc\User\Component;
use Phalcon\Security;

/**
 * Class Auth
 *
 * @package Backend\Library
 * @property \Redis redis
 */
class Auth extends Component
{
    const M_SITE = 1;
    const M_ADMIN = 2;
    const M_CASH = 4;


    const VERIFICATION_CODE_EXPIRE = 240; //4 мин
    const VERIFICATION_CODE_SOL = 'mUAN73p6Djq9GJm3YEVIio3s2EUfSE';

    private $_options = [
        'session_key' => 'identity',
        'forgot_fields' => 'id, email',
        'forgot_key' => 'Auth:Forgot:Tokens',
        'module' => self::M_SITE,
        'remember_expire' => 8600,
        'remember_cookie' => 'rmt',
        'remember_key' => 'Auth:Remember:Tokens',
    ];

    /**
     * @var string
     */
    private $_forgot_token;

    /**
     * @var boolean
     */
    private $_isIdentity;

    /**
     * @var array
     */
    private $_errorMessages = [];

    /**
     * @var CRUD
     */
    private $_crud;

    /**
     * @var UserInterface
     */
    private $_User;

    /**
     * Auth constructor.
     * @param string $userClass
     * @param array $_options
     */
    public function __construct(string $userClass, array $_options = [])
    {
        $this->_options = array_merge($this->_options, $_options);

        $this->_crud = new CRUD($userClass);

        $this->checkAuth();
    }

    /**
     * Идентифицирован ли пользователь
     *
     * @return bool
     */
    public function isIdentity()
    {
        if ($this->_isIdentity === null) {
            $this->_isIdentity = (bool)$this->session->has($this->_options['session_key']);
        }

        return ($this->_isIdentity === true);
    }

    /**
     * @param string $login
     * @param string $pass
     * @param bool $needRemember
     * @param bool $checkPass
     *
     * @return bool
     */
    public function logIn($login, $pass, $needRemember = false, $checkPass = true)
    {
        if ($this->_eventsManager instanceof Manager) {
            $this->_eventsManager->fire('auth:beforeAuth', $this, [
                'login' => $login,
                'pass' => $pass,
                'needRemember' => $needRemember,
                'checkPass' => $checkPass,
            ]);
        }

        $User = $this->_crud->findUser($login);

        if (!$User) {
            $message = 'Пользователь не найден';

            $this->appendMessage($message, 'login');

            $this->_crud->saveFailedLogin($login, $message);

            return false;
        }

        /**@var User $User */
        if((int)$User->getStatus() !== 1){
            $message = 'Пользователь не подтверждён';

            $this->appendMessage($message, 'login');

            $this->_crud->saveFailedLogin($login, $message);

            return false;
        }

        /**
         * Проверяем пароль
         */
        if ($checkPass && !$this->security->checkHash($pass, $User->getPassword())) {

            $this->logOut();

            $message = 'Неверный пароль';

            $this->appendMessage($message, 'password');

            $this->_crud->saveFailedLogin($login, $message);

            return false;
        }

        if ($this->_eventsManager instanceof Manager) {
            $fire = $this->_eventsManager->fire('auth:afterUserChecked', $this, $User);

            if ($fire === false) {
                return false;
            }
        }

        $data = [
            'id' => (int)$User->getId(),
            'user_id' => (int)$User->getId(),
            'ip' => $this->request->getClientAddress(),
            'email' => $User->getEmail(),
            'role' => $User->getRole(),
            'token' => sha1($User->getEmail() . $User->getPassword() . $this->request->getUserAgent()),
            'agent' => md5($this->request->getUserAgent()),
            'created' => time(),
        ];

        $this->_setIdentity($data);

        if ($needRemember) {
            $this->_setRemember($data);
        }

        if ($this->_eventsManager instanceof Manager) {

            $this->_eventsManager->fire('auth:afterAuth', $this, [
                'login' => $login,
                'pass' => $pass,
                'needRemember' => $needRemember,
                'checkPass' => $checkPass,
            ]);

        }

        return true;
    }

    public function isRole($role)
    {
        if (!$this->isIdentity()) {
            return false;
        }

        return $this->getIdentity('role') === $role;
    }

    /**
     * Выходим
     */
    public function logOut()
    {
        $this->cookies->get($this->_options['remember_cookie'])->delete();
        $this->session->remove($this->_options['session_key']);

        $this->_isIdentity = null;
    }

    /**
     * Записываем сообщение об ошибке
     *
     * @param $message
     * @param $field
     */
    public function appendMessage($message, $field = '')
    {
        $this->_errorMessages[$field] = $message;
    }

    /**
     * Отдаём сообщения об ошибках
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->_errorMessages;
    }

    /**
     * Получаем информацию об авторизованном пользователе
     *
     * @param null $key
     *
     * @return mixed
     */
    public function getIdentity($key = null)
    {
        $identity = $this->session->get($this->_options['session_key']);

        if ($key !== null) {
            return $identity[$key];
        }

        return $identity;
    }

    /**
     * Проверяем есть ли кука для 'Запомнить меня'
     *
     * @return boolean
     */
    public function isRemember()
    {
        return $this->cookies->has($this->_options['remember_cookie']);
    }

    /**
     * Получает данные для входа через режим 'Запомнить меня'
     *
     * @return array
     */
    public function getRemember()
    {
        $token = $this->cookies->get($this->_options['remember_cookie'])->getValue();
        $data = $this->_crud->getRememberData($this->_options['remember_key'], $token);

        if (!$data) {
            $this->cookies->get($this->_options['remember_cookie'])->delete();

            return [];
        }

        return $data;
    }

    /**
     * @param int $userId
     *
     * @return boolean
     */
    public function forgotMakeToken($userId)
    {
        $token = preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(openssl_random_pseudo_bytes(32)));

        if (!$this->_crud->setForgotData($this->_options['forgot_key'], $token, $userId)) {
            $this->appendMessage('Внезапно возникла непонятная ошибка ¯\_(ツ)_/¯', 'remember');

            return false;
        }

        $this->_forgot_token = $token;

        return true;
    }

    /**
     * @param $token
     *
     * @return int
     */
    public function forgotGetUserId($token)
    {
        return (int)$this->_crud->getForgotData($this->_options['forgot_key'], $token);
    }

    /**
     * @return string
     */
    public function getForgotToken()
    {
        return $this->_forgot_token;
    }

    /**
     * @param bool $refresh
     *
     * @return UserInterface
     */
    public function getUser($refresh = false)
    {
        if (null === $this->_User || $refresh) {
            $this->_User = $this->_crud->findUserById($this->getIdentity('user_id'));
        }

        return $this->_User;
    }

    /**
     * Проверка авторизации
     *
     * @return bool
     */
    private function checkAuth()
    {
        /**
         * Проверяем не была ли сессия переставлена из другого браузера
         */
        if ($this->isIdentity()) {
            $identity = $this->getIdentity();

            if ($identity['agent'] !== md5($this->request->getUserAgent())) {

                $this->appendMessage('There was a substitution of session', 'session');

                $this->logOut();

                return false;
            }
        }

        /**
         * Проверяем совпадают ли данные в сессии с данными в куки
         * если не совпадают - разлогиниваем пользователя
         */
        if ($this->isIdentity() && $this->isRemember()) {
            $identity = $this->getIdentity();
            $remember = $this->getRemember();

            if (empty($identity) || empty($remember)) {
                return false;
            }

            if ($identity['token'] !== $remember['token']) {
                $this->logOut();

                return false;
            }
        }

        /**
         * Идентифицируем пользователя если есть кука и он не идентифицирован
         */
        if (!$this->isIdentity() && $this->isRemember()) {

            return $this->checkRemember();
        }

        return $this->isIdentity();
    }

    private function checkRemember()
    {
        $remember = $this->getRemember();

        if (!count($remember)) {
            $this->logOut();

            return false;
        }

        $User = $this->_crud->findUser($remember['login']);

        if (!$User) {
            $this->logOut();

            return false;
        }

        $token = sha1($User->getEmail() . $User->getPassword() . $this->request->getUserAgent());

        if ($remember['token'] === $token) {
            $this->logOut();

            return false;
        }

        /**
         * Проверяем не истекла ли кука
         */
        if ((time() - $this->_options['remember_expire']) >= $remember['created']) {
            $this->logOut();

            return false;
        }

        $this->logIn($User->getEmail(), '', false, false);

        return true;
    }

    /**
     * Записываем данные об авторизации
     *
     * @param array $data
     */
    private function _setIdentity(array $data)
    {
        $this->session->set($this->_options['session_key'], $data);
        $this->_isIdentity = true;
    }

    /**
     * Записываем данные для авторизации по куке
     *
     * @param array $data
     */
    private function _setRemember(array $data)
    {
        $expire = time() + $this->_options['remember_expire'];

        if ($this->_crud->setRememberData($this->_options['remember_key'], $data['token'], $data)) {
            $this->cookies->set($this->_options['remember_cookie'], $data['token'], $expire, '/');
        }
    }

    /**
     * Генерирует новый пароль пользователю
     *
     * @param UserInterface $user
     *
     * @return string
     */
    public function generatePassword($user)
    {
        $newPassword = '';
        try {

            $rand = new Security\Random();
            $newPassword = substr(strtolower($rand->base64Safe(4)), 0, 5);
            $user->setPassword($this->security->hash($newPassword));
        } catch (\Exception $e) {

        }

        return $newPassword;
    }
}
