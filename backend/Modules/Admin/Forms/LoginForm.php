<?php

namespace Backend\Modules\Admin\Forms;

use Backend\Library\Service\Auth;
use Backend\Library\Traits\AddErrorToForm;
use Phalcon\Forms\Element;
use Phalcon\Forms\Form;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;

/**
 * Class LoginForm
 * @package Backend\Modules\Admin\Forms
 * @property Auth $auth
 */
class LoginForm extends Form
{
    use AddErrorToForm;

    private $login;
    private $password;
    private $remember;


    public function beforeValidation($data, $entity)
    {
        $this->setEntity($entity);

        $this->login = $this->getValue('login');
        $this->password = $this->getValue('password');
        $this->remember = !empty($this->getValue('remember'));

        if(!$this->auth->logIn($this->login,$this->password,$this->remember)){
            foreach ($this->auth->getMessages() as $field => $message){
                $this->appendError($message,$field);
            }
        }

        return true;
    }

    /**
     * @param Message[] $messages
     */
    public function afterValidation($messages)
    {
        if ( count($messages) > 0 ) {
            foreach ($messages as $message){
                $field = $this->get($message->getField());
                $field->setAttribute('class',$field->getAttribute('class').' parsley-error');
            }
            return;
        }
    }

    public function initialize()
    {

        $this->setAction($this->url->get(['for'=>'admin.auth.login']));
        /**
         * Email
         */
        $this->add((new Element\Text('login', [
            'class'       => 'form-control',
            'placeholder' => 'Введите Email',
            'required'    => 'true'
        ]))->addValidator(new Validator\Email([
            'message'      => 'Неправильный Email',
            'cancelOnFail' => true,
        ]))->addFilter('trim')->addFilter('string')->addFilter('striptags'));

        /**
         * Password
         */
        $this->add((new Element\Password('password', [
            'class'       => 'form-control',
            'placeholder' => 'Введите пароль',
            'required'    => 'true'
        ]))->addValidator(new Validator\PresenceOf([
            'message'      => 'Для входа нужно ввести пароль',
            'cancelOnFail' => true,
        ]))->addFilter('trim'));

        /**
         * Remember
         */
        $this->add(new Element\Check('remember', [
            'value' => 1,
            'class' => 'flat'
        ]));
    }

}