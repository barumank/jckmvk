<?php


namespace Backend\Modules\Site\Forms;

use Backend\Library\Phalcon\Validation\BuilderUniqueness;
use Backend\Library\Service\Auth;
use Backend\Library\Traits\AddErrorToForm;
use Backend\Models\MySQL\DAO\User;
use Backend\Models\MySQL\DAO\UserPaymentMethod;
use Phalcon\Forms\Element;
use Phalcon\Forms\Form;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;

/**
 * Class SignUpForm
 * @package Backend\Modules\Site\Forms
 * @property Auth $auth
 */
class SignUpForm extends Form
{
    use AddErrorToForm;

    private $password;

    public function beforeValidation($data, $entity)
    {
        $this->setEntity($entity);

        return true;
    }

    /**
     * @param Message[] $messages
     */
    public function afterValidation($messages)
    {
        if (count($messages) > 0) {
            return;
        }
    }

    public function login()
    {
        /**@var User $user */
        $user = $this->getEntity();
        if (!$this->auth->logIn($user->getEmail(), $this->password)) {
            return false;
        }
        return true;
    }

    public function save()
    {
        /**@var User $user */
        $user = $this->getEntity();

        $this->password = $this->getValue('password_field');
        if (!empty($this->password)) {
            $user->setPassword($this->security->hash($this->password));
        }
        if (!$user->setRole(User::ROLE_USER)->save()) {
            $this->appendError('Ошибка сохранения', 'name');
            return false;
        }

        return true;
    }

    public function initialize($entity, $options)
    {

        /**
         * name
         */
        $this->add((new Element\Text('name', [
            'id' => 'id-name',
            'placeholder' => 'Имя',
            'class' => 'form-control',
        ]))->addValidator(new Validator\PresenceOf([
            'message' => 'Обязательное поле "Имя" не заполнено.',
        ]))->addValidator(new Validator\StringLength([
            'max' => 50,
            'min' => 2,
            'messageMaximum' => 'Максимальная длинна 50 символов',
            'messageMinimum' => 'Минимальная длинна 2 символа',
        ]))->setFilters(["trim", "string"])
            ->setLabel('Имя'));

        /**
         * last_name
         */
        $this->add((new Element\Text('surname', [
            'id' => 'id-surname',
            'placeholder' => 'Фамилия',
            'class' => 'form-control',
        ]))->addValidator(new Validator\PresenceOf([
            'message' => 'Обязательное поле "Фамилия" не заполнено.',
        ]))->addValidator(new Validator\StringLength([
            'max' => 50,
            'messageMaximum' => 'Максимальная длинна 50 символов',
        ]))->setFilters(["trim", "string"])
            ->setLabel('Фамилия'));

        /**
         * email
         */
        $this->add((new Element\Text('email', [
            'id' => 'id-email',
            'placeholder' => 'Email',
            'class' => 'form-control',
        ]))->addValidator(new Validator\PresenceOf([
            'message' => 'Обязательное поле "Email" не заполнено.',
        ]))->addValidator(new Validator\Email([
            'message' => 'Неверный email',
        ]))->addValidator(new Validator\StringLength([
            'max' => 50,
            'min' => 4,
            'messageMaximum' => 'Максимальная длинна 50 символов',
            'messageMinimum' => 'Минимальная длинна 4 символа',
        ]))->addValidator(new BuilderUniqueness([
            'fieldName' => 'email',
            'message' => 'Пользователь с данным email уже существует',
            'builder' => User::getBuilder(),
        ]))->setFilters(["trim", "string"])
            ->setLabel('Email'));

        /**
         * phone
         */
        $this->add((new Element\Text('phone', [
            'class' => 'form-control phoneMask',
            'id' => 'id-phone',
            'placeholder' => 'Номер телефона',
        ]))->addValidator(new Validator\PresenceOf([
            'message' => 'Обязательное поле "Номер телефона" не заполнено.',

        ]))->addValidator(new Validator\Regex([
            'pattern' => '#^\+?\d{10,12}$#is',
            'message' => 'Неверный номер телефона',

        ]))->setFilters(["trim", "string"])
            ->setLabel('Номер телефона'));

        /**
         * Password
         */
        $this->add(
            (new Element\Password('password_field', [
                'class' => 'form-control',
                'id' => 'id-password',
                'placeholder' => 'Пароль',
            ]))->addValidator(new Validator\PresenceOf([
                'message' => 'Обязательное поле "Пароль" не заполнено.',

            ]))
                ->addValidator(new Validator\Confirmation([
                    'message' => 'Пароли не совпадают',
                    'with' => 'password_confirm_field',
                ]))
                ->addFilter('trim')
                ->setLabel('Пароль'));

        /**
         * password_confirm
         */
        $this->add(
            (new Element\Password('password_confirm_field', [
                'class' => 'form-control',
                'id' => 'id-password_confirm',
                'placeholder' => 'Повторите пароль',
            ]))->addValidator(new Validator\PresenceOf([
                'message' => 'Повторите пароль',

            ]))->addValidator(new Validator\Confirmation([
                'message' => 'Пароли не совпадают',
                'with' => 'password_field',
            ]))
                ->addFilter('trim')
                ->setLabel('Повторите пароль'));
        /**
         * payment_method_id
         */
        $this->add((new Element\Select('payment_method_id',
            UserPaymentMethod::find(), [
                'class' => 'form-control',
                'using' => ['id', 'name'],
            ]))->addValidator(new Validator\PresenceOf([
            'message' => 'Обязательное поле "Предпочитаемый способ оплаты" не заполнено.',
        ]))->setFilters(["int"])
            ->setLabel('Предпочитаемый способ оплаты'));

        /**
         * payment_number
         */
        $this->add((new Element\Text('payment_number', [
            'class' => 'form-control',
            'id' => 'id-payment_number',
            'placeholder' => 'Номер кошелька',
        ]))->addValidator(new Validator\PresenceOf([
            'message' => 'Обязательное поле "Номер кошелька" не заполнено.',

        ]))->addValidator(new Validator\StringLength([
            'max' => 50,
            'messageMaximum' => 'Максимальная длинна 50 символов',

        ]))->setFilters(["trim", "string"])
            ->setLabel('Номер кошелька'));

        /**
         * skype
         */
        $this->add((new Element\Text('skype', [
            'class' => 'form-control',
            'id' => 'id-skype',
            'placeholder' => 'Skype',
        ]))->addValidator(new Validator\StringLength([
            'max' => 50,
            'messageMaximum' => 'Максимальная длинна 50 символов',
        ]))->setFilters(["trim", "string"])
            ->setLabel('Skype'));

    }

}