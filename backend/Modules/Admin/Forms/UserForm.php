<?php

namespace Backend\Modules\Admin\Forms;

use Backend\Library\Service\AjaxUploadService\Library\FileResponse;
use Backend\Library\Traits\AddErrorToForm;
use Backend\Library\Transliterator;
use Backend\Models\MySQL\DAO\PartnerSchedule;
use Backend\Models\MySQL\DAO\Service;
use Backend\Models\MySQL\DAO\ServiceCategory;
use Backend\Models\MySQL\DAO\ServiceToPartner;
use Backend\Models\MySQL\DAO\User;
use Backend\Models\MySQL\DAO\UserPartnerProperty;
use Backend\Models\MySQL\TDO\Partner;
use Phalcon\Forms\Element;
use Phalcon\Forms\Form;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Backend\Models\MySQL\DAO\CarMark;
use Backend\Models\MySQL\DAO\CarModel;
use Backend\Models\MySQL\DAO\PartnerToCarModel;

/**
 * Class UserForm
 * @package Backend\Modules\Admin\Forms
 * @property \Backend\Library\Service\AjaxUploadService\Manager $ajaxUploadService
 * @property \Backend\Library\Service\PartnerService\Manager $partnerService
 */
class UserForm extends Form
{
    use AddErrorToForm;

    public function beforeValidation($data, $entity)
    {
        $this->setEntity($entity);

        $password = $this->request->getPost('password_field', 'trim');
        $passwordConfirm = $this->request->getPost('password_confirm_field', 'trim');
        if (!empty($password)) {
            if ($password !== $passwordConfirm) {
                $this->appendError('Passwords do not match', 'password_confirm_field');
                return false;
            }
        }

        return true;
    }

    /**
     * @param Message[] $messages
     */
    public function afterValidation($messages)
    {
        if (count($messages) > 0) {
            foreach ($messages as $message) {
                $field = $this->get($message->getField());
                if (!empty($field)) {
                    $field->setAttribute('class', $field->getAttribute('class') . ' parsley-error');
                }
            }
            return;
        }

        /**@var \Backend\Models\MySQL\TDO\User $user */
        $user = $this->getEntity();
        $is_send_news = $this->getValue('is_send_news_check') ? 1 : 0;
        $is_blocked = $this->getValue('is_blocked_check') ? 1 : 0;
        $is_send_sms = $this->getValue('is_send_sms_check') ? 1 : 0;
        $is_send_email = $this->getValue('is_send_email_check') ? 1 : 0;
        $user
            ->setIsSendNews($is_send_news)
            ->setIsBlocked($is_blocked)
            ->setIsSendSms($is_send_sms)
            ->setIsSendEmail($is_send_email);

        $password = $this->getValue('password_field');
        if (!empty($password)) {
            $user->setPassword($this->security->hash($password));
        }

    }

    public function save()
    {
        try {

            /**@var \Backend\Models\MySQL\TDO\User $userTdo */
            $userTdo = $this->getEntity();
            $user = $userTdo->getUser();
            $userProperty = $userTdo->getUserProperty();

            /**
             * лого
             */
            $code = $this->getValue('image_code');
            $imageRedisFile = $this->ajaxUploadService->getFileByKey($code);

            $manager = new TxManager();
            $transaction = $manager->get();
            $user->setTransaction($transaction);
            $userProperty->setTransaction($transaction);
            if (!$user->save()) {
                $transaction->rollback();
            }
            if ($user->getRole() === User::ROLE_PARTNER) {
                $partnerProperty = UserPartnerProperty::findByUserId($user->getId());
                if (empty($partnerProperty)) {
                    $partnerProperty = (new UserPartnerProperty())
                        ->setPartnerId($user->getId());

                    $slug = $partnerProperty->getSlug();
                    if (empty($slug)) {
                        $slug = $user->getName();
                    }
                    $partnerProperty
                        ->setSlug((new Transliterator())->translate(trim($slug) . '-' . $user->getId()))
                        ->setTransaction($transaction);
                    if (!$partnerProperty->save()) {
                        $transaction->rollback();
                    }
                }
            }
            $userProperty->setUserId($user->getId());
            if (!empty($imageRedisFile)) {
                $userProperty->setImage($imageRedisFile->getName());
            }
            if (!$userProperty->save()) {
                $transaction->rollback();
            }
            $transaction->commit();
            if (!empty($imageRedisFile)) {
                $path = "{$this->config->dirs->user}/{$user->getId()}/{$imageRedisFile->getName()}";
                $this->ajaxUploadService->moveTo($imageRedisFile->getKey(), $path);
            }
            return true;
        } catch (\Exception $e) {
            $this->appendError('data is not unique', 'name');
            return false;
        }
    }


    /**
     * @param \Backend\Models\MySQL\TDO\User $entity
     * @param array $options
     */
    public function initialize($entity, $options)
    {

        /**
         * is_send_news_check
         */
        $this->add((new Element\Check('is_send_news_check', [
            'value' => '1',
            'class' => 'js-switch'
        ]))
            ->setLabel('send news alerts'));

        if (!empty($entity)
            && $entity->getIsSendNews()) {
            $this->get('is_send_news_check')->setAttribute('checked', 'checked');
        }

        /**
         * is_send_sms_check
         */
        $this->add((new Element\Check('is_send_sms_check', [
            'value' => '1',
            'class' => 'js-switch'
        ]))
            ->setLabel('send SMS notifications'));

        if (!empty($entity)
            && $entity->getIsSendSms()) {
            $this->get('is_send_sms_check')->setAttribute('checked', 'checked');
        }

        /**
         * is_blocked_check
         */
        $this->add((new Element\Check('is_blocked_check', [
            'value' => '1',
            'class' => 'js-switch'
        ]))->setLabel('block user'));

        if (!empty($entity)
            && $entity->getIsBlocked()) {
            $this->get('is_blocked_check')->setAttribute('checked', 'checked');
        }

        /**
         * is_blocked_check
         */
        $this->add((new Element\Check('is_send_email_check', [
            'value' => '1',
            'class' => 'js-switch'
        ]))->setLabel('send Email notifications'));

        if (!empty($entity)
            && $entity->getIsSendEmail()) {
            $this->get('is_send_email_check')->setAttribute('checked', 'checked');
        }

        /**
         * role
         */
        $this->add((new Element\Select('role',
            User::ROLE_LABEL_MAP, [
                'class' => 'form-control',
                'id' => 'id-role',
                'useEmpty' => true,
                'emptyText' => 'Select role...',
                'emptyValue' => '',
            ]))->addValidator(new Validator\PresenceOf([
            'message' => 'Mandatory field "role" is not filled.',
            'cancelOnFail' => true,
        ]))->setFilters(["trim", "string"])
            ->setLabel('Role:'));
        if (empty($entity) || empty($entity->getId())) {
            $this->get('role')->setDefault(User::ROLE_USER);
        }

        /**
         * name
         */
        $this->add((new Element\Text('name', [
            'class' => 'form-control',
            'id' => 'id-name',
            'placeholder' => 'Name',
        ]))->addValidator(new Validator\PresenceOf([
            'message' => 'Mandatory field "name" is not filled.',
            'cancelOnFail' => true,
        ]))->addValidator(new Validator\StringLength([
            'max' => 50,
            'min' => 2,
            'messageMaximum' => 'Maximum length 50 characters',
            'messageMinimum' => 'Minimum length 2 characters',
            'cancelOnFail' => true,
        ]))->setFilters(["trim", "string"])
            ->setLabel('Name:'));

        /**
         * email
         */
        $this->add((new Element\Text('email', [
            'class' => 'form-control',
            'id' => 'id-email',
            'placeholder' => 'Email',
        ]))->addValidator(new Validator\PresenceOf([
            'message' => 'Mandatory field "email" is not filled.',
            'cancelOnFail' => true,
        ]))->addValidator(new Validator\Email([
            'message' => 'Mandatory field "email" is not valid.',
            'cancelOnFail' => true,
        ]))->addValidator(new Validator\StringLength([
            'max' => 50,
            'min' => 4,
            'messageMaximum' => 'Maximum length 50 characters',
            'messageMinimum' => 'Minimum length 4 characters',
            'cancelOnFail' => true,
        ]))->setFilters(["trim", "string"])
            ->setLabel('Email:'));

        /**
         * phone
         */
        $this->add((new Element\Text('phone', [
            'class' => 'form-control',
            'id' => 'id-phone',
            'placeholder' => 'Phone',
        ]))->addValidator(new Validator\PresenceOf([
            'message' => 'Mandatory field "phone" is not filled.',
            'cancelOnFail' => true,
        ]))->addValidator(new Validator\StringLength([
            'max' => 30,
            'min' => 5,
            'messageMaximum' => 'Maximum length 30 characters',
            'messageMinimum' => 'Minimum length 5 characters',
            'cancelOnFail' => true,
        ]))->setFilters(["trim", "string"])
            ->setLabel('Phone:'));

        /**
         * last_name
         */
        $this->add((new Element\Text('last_name', [
            'class' => 'form-control',
            'id' => 'id-last_name',
            'placeholder' => 'last name',
        ]))->addValidator(new Validator\StringLength([
            'max' => 50,
            'messageMaximum' => 'Maximum length 50 characters',
            'cancelOnFail' => true,
        ]))->setFilters(["trim", "string"])
            ->setLabel('Last name:'));

        /**
         * Password
         */
        $this->add(
            (new Element\Password('password_field', [
                'class' => 'form-control',
                'id' => 'id-password',
                'placeholder' => 'Password',
            ]))
                ->addFilter('trim')
                ->setLabel('Password:'));

        if (empty($entity) || empty($entity->getId())) {
            $this->get('password_field')
                ->addValidator(new Validator\PresenceOf([
                    'message' => 'Enter password',
                    'cancelOnFail' => true,
                ]))
                ->addValidator(new Validator\Confirmation([
                    'message' => 'Passwords do not match',
                    'with' => 'password_confirm_field',
                ]));
        }

        /**
         * password_confirm
         */
        $this->add(
            (new Element\Password('password_confirm_field', [
                'class' => 'form-control',
                'id' => 'id-password_confirm',
                'placeholder' => 'Password confirm',
            ]))
                ->addFilter('trim')
                ->setLabel('Password confirm:'));
        if (empty($entity) || empty($entity->getId())) {
            $this->get('password_confirm_field')
                ->addValidator(new Validator\PresenceOf([
                    'message' => 'Repeat password you entered',
                    'cancelOnFail' => true,
                ]))
                ->addValidator(new Validator\Confirmation([
                    'message' => 'Passwords do not match',
                    'with' => 'password_field',
                ]));
        }

        $this->add(new Element\Hidden('image_code', [
            'id' => 'id-image_code',
            'class' => 'upload_code'
        ]));
        if ($this->request->isPost()) {
            $this->get('image_code')
                ->setAttribute('value', $this->request->getPost('image_code'));
        }

    }

}