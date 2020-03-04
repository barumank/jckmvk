<?php


namespace Backend\Modules\API\CRM\v1\Validations;


use Backend\Library\Phalcon\Validation\BuilderUniqueness;
use Backend\Library\Phalcon\Validation\CheckExistsValidator;
use Backend\Library\Phalcon\Validation\GetErrorMessages;
use Backend\Models\MySQL\DAO\User;
use Phalcon\Di\FactoryDefault;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;

class UserSettingsValidation extends Validation
{
    use GetErrorMessages;

    public function initialize()
    {
        $this->add(
            'id',
            new PresenceOf(
                [
                    'message' => 'пользователь не найден',
                    'cancelOnFail' => true,
                ]
            )
        );
        $this->add(
            'id',
            new CheckExistsValidator(
                [
                    'message' => 'пользователь не найден',
                    'fieldName'=>'u.id',
                    'builder'=>User::getBuilder(),
                    'cancelOnFail' => true,
                ]
            )
        );
        $this->setFilters('id', 'int');

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

       /**@var \Backend\Library\Service\Auth $auth */
        $auth = FactoryDefault::getDefault()->get('auth');
        $this->add(
            'email',
            new BuilderUniqueness([
                'builder'=>User::getBuilder(),
                'fieldName'=>'u.email',
                'excludeField'=>'u.id',
                'excludeValue'=>$auth->getIdentity('user_id'),
                'message' => 'Пользователь с данным email уже существует',
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
        $this->setFilters('name', 'trim');
        $this->setFilters('last_name', 'trim');

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
