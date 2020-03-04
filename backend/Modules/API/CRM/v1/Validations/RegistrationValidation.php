<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 02.01.20
 * Time: 11:07
 */

namespace Backend\Modules\API\CRM\v1\Validations;

use Phalcon\Validation;
use Phalcon\Validation\Validator\{PresenceOf,Confirmation,Email};

class RegistrationValidation extends Validation
{
    public function initialize()
    {
        $this->add(
            'email',
            new PresenceOf(
                [
                    'message' => '"email" обязательное поле',
                ]
            )
        );
        $this->add(
            'email',
            new Email([
                'message' => 'некорректный email',
                'cancelOnFail' => true,
            ])
        );
        $this->add(
            'name',
            new PresenceOf(
                [
                    'message' => '"Имя" обязательное поле',
                ]
            )
        );
        $this->add(
            'password',
            new PresenceOf(
                [
                    'message' => '"Пароль" обязательное поле',
                ]
            )
        );
        $this->add(
            'confirm_password',
            new PresenceOf(
                [
                    'message' => 'Повторите пароль',
                ]
            )
        );
        $this->add(
            'confirm_password',
            new Confirmation(
                [
                    "message" => "Пароли не совпадают",
                    "with"    => "password",
                ]
            )
        );

    }
}
