<?php

namespace Backend\Modules\API\CRM\v1\Validations;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class ProductValidation extends Validation
{
    public function initialize()
    {
        $this->add(
            'name',
            new PresenceOf(
                [
                    'message' => '"Название товара" обязательное поле',
                ]
            )
        );

        $this->add(
            'vendor_code',
            new PresenceOf(
                [
                    'message' => '"Артикул" обязательное поле',
                ]
            )
        );

        $this->add('amount',
            new PresenceOf(
                [
                    'message' => '"Цена со скидкой" обязательное поле',
                ]
            )
        );

        $this->setFilters('name', 'trim');
        $this->setFilters('vendor_code','trim');
        $this->setFilters('amount','float');
        $this->setFilters('rrc','float');
        $this->setFilters('discount','float');
    }

}
