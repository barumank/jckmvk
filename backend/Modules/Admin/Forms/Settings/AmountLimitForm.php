<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 03.04.19
 * Time: 16:45
 */

namespace Backend\Modules\Admin\Forms\Settings;

use Backend\Library\Service\SettingsService\TDO\AmountLimit;
use Backend\Library\Traits\AddErrorToForm;
use Phalcon\Forms\Element;
use Phalcon\Forms\Form;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;

/**
 * Class AmountLimitForm
 * @package Backend\Modules\Admin\Forms\Settings
 * @property \Backend\Library\Service\SettingsService\Manager $settingsService
 */
class AmountLimitForm extends Form
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
        /**@var AmountLimit $amountLimit */
        $amountLimit = $this->getEntity();
        return $this->settingsService->setPercentPartnerProgramObject($amountLimit);
    }

    /**
     * @param AmountLimit $entity
     * @param array $options
     */
    public function initialize($entity, $options)
    {
        /**
         * amountLimit
         */
        $this->add((new Element\Text('amountLimit', [
            'class' => 'form-control',
            'id' => 'id-amountLimit',
            'placeholder' => 'amount limit',
        ]))->addValidator(new Validator\Regex([
            'pattern' => '/^(?:\+|\-)?(?:\d+)?(?:\.|\,)?(?:\d+)?$/',
            'message' => 'Field "amountLimit" is not valid.',
            'cancelOnFail' => true,
        ]))->setFilters(["MoneyFilter"])
            ->setLabel('Amount limit:'));

    }

}