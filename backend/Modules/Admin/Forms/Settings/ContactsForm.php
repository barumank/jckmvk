<?php

namespace Backend\Modules\Admin\Forms\Settings;
use Backend\Library\Service\SettingsService\TDO\PercentUser;
use Backend\Library\Service\SettingsService\TDO\Contacts;
use Backend\Library\Traits\AddErrorToForm;
use Phalcon\Forms\Element;
use Phalcon\Forms\Form;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;

/**
 * Class ContactsForm
 * @package Backend\Modules\Admin\Forms\Settings
 * @property \Backend\Library\Service\SettingsService\Manager $settingsService
 */
class ContactsForm extends Form
{
    use AddErrorToForm;

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
            foreach ($messages as $message) {
                $field = $this->get($message->getField());
                if (!empty($field)) {
                    $field->setAttribute('class', $field->getAttribute('class') . ' parsley-error');
                }
            }
        }
    }

    public function save()
    {
        /**@var Contacts $contacts */
        $contacts = $this->getEntity();
        return $this->settingsService->setContactsObject($contacts);
    }


    /**
     * @param PercentUser $entity
     * @param array $options
     */
    public function initialize($entity, $options)
    {
        /**
         * infoEmail
         */
        $this->add((new Element\Text('infoEmail', [
            'class' => 'form-control',
            'id' => 'id-infoEmail',
            'placeholder' => 'Email',
        ]))
            ->setLabel('Email:'));

        /**
         * infoEmail
         */
        $this->add((new Element\Text('infoPhone', [
            'class' => 'form-control',
            'id' => 'id-infoPhone',
            'placeholder' => 'Phone',
        ]))
            ->setLabel('Phone:'));

        /**
         * infoEmail
         */
        $this->add((new Element\Text('address', [
            'class' => 'form-control',
            'id' => 'id-address',
            'placeholder' => 'Address',
        ]))
            ->setLabel('Address:'));

    }
}