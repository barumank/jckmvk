<?php


namespace Backend\Library\Phalcon\Validation;


use Backend\Library\Service\Auth;
use Backend\Models\MySQL\DAO\User;
use Phalcon\Security;
use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;

class PasswordValidator extends Validator
{
    public function validate(Validation $validation, $attribute)
    {
        $oldPassword = $validation->getValue($attribute);
        /**@var User $user*/
        $user = $this->getOption('user');
        $message = $this->getOption('message');
        /**@var Security $security*/
        $security = $this->getOption('security');

        if(!$security->checkHash($oldPassword, $user->getPassword())){
            $validation->appendMessage(new Message($message, $attribute));
            return false;
        }
        return true;
    }
}