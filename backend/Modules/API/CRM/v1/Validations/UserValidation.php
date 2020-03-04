<?php


namespace Backend\Modules\API\CRM\v1\Validations;


use Backend\Models\MySQL\DAO\User;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\InclusionIn;

class UserValidation extends Validation
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
        $this->setFilters('last_name', 'trim');

        $this->add(
            'role',
            new PresenceOf(
                [
                    'message' => '"Роль" обязательное поле',
                ]
            )
        );

        $this->add(
            'role',
            new InclusionIn(
                [
                    'message' => '"Роль" обязательное поле',
                    'domain' => [User::ROLE_USER, User::ROLE_ADMIN]
                ]
            )
        );


        if ($this->request->hasPost('password')) {
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
                        "with" => "password",
                    ]
                )
            );
        }


    }
}
