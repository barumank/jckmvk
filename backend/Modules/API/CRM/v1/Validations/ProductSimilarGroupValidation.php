<?php

namespace Backend\Modules\API\CRM\v1\Validations;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class ProductSimilarGroupValidation extends Validation
{
    public function initialize()
    {
        $this->add(
            'product_id',
            new PresenceOf(
                [
                    'message' => 'Обязательное поле',
                ]
            )
        );

        $this->add(
            'analog_id',
            new PresenceOf(
                [
                    'message' => 'Обязательное поле',
                ]
            )
        );

        $this->add('group_id',
            new PresenceOf(
                [
                    'message' => 'Обязательное поле',
                ]
            )
        );
    }

}
