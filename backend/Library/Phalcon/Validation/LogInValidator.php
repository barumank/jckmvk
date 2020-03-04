<?php


namespace Backend\Library\Phalcon\Validation;


use Backend\Library\Service\Auth;
use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;

class LogInValidator extends Validator
{
    public function validate(Validation $validation, $attribute)
    {

        /**@var Auth $auth */
        $auth = $this->getOption('auth');
        $loginField = $attribute;
        $passwordField = $this->getOption('passwordField');
        $rememberField = $this->getOption('rememberField');

        $login = $validation->getValue($attribute);
        $password = $validation->getValue($passwordField);
        $remember = $validation->getValue($rememberField);
        $remember = empty($remember) ? false : true;

        if (!$auth->logIn($login, $password, $remember)) {
            foreach ($auth->getMessages() as $field => $message) {
                switch ($field) {
                    case 'remember':
                        $validation->appendMessage(new Message($message, $rememberField, 'login'));
                        break;
                    case 'session':
                        $validation->appendMessage(new Message($message, $loginField, 'login'));
                        break;
                    case 'login':
                        $validation->appendMessage(new Message($message, $loginField, 'login'));
                        break;
                    case 'password':
                        $validation->appendMessage(new Message($message, $passwordField, 'login'));
                        break;
                }
            }
            return false;
        }
        return true;
    }
}