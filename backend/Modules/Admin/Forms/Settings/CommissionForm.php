<?php

namespace Backend\Modules\Admin\Forms\Settings;

use Backend\Library\Service\SettingsService\TDO\PercentUser;
use Backend\Library\Traits\AddErrorToForm;
use Phalcon\Forms\Element;
use Phalcon\Forms\Form;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;

/**
 * Class CommissionForm
 * @package Backend\Modules\Admin\Forms\Settings
 * @property \Backend\Library\Service\SettingsService\Manager $settingsService
 */
class CommissionForm extends Form
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
        /**@var PercentUser $commission */
        $commission = $this->getEntity();
        return $this->settingsService->setCommissionObject($commission);
    }


    /**
     * @param PercentUser $entity
     * @param array $options
     */
    public function initialize($entity, $options)
    {
        /**
         * commission
         */
        $this->add((new Element\Text('commission', [
            'class' => 'form-control',
            'id' => 'id-commission',
            'placeholder' => 'commission',
        ]))->addValidator(new Validator\Regex([
            'pattern' => '/^(?:\+|\-)?(?:\d+)?(?:\.|\,)?(?:\d+)?$/',
            'message' => 'Field "commission" is not valid.',
            'cancelOnFail' => true,
        ]))->setFilters(["MoneyFilter"])
            ->setLabel('Commission(%):'));

    }

}