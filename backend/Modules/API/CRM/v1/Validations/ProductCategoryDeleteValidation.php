<?php


namespace Backend\Modules\API\CRM\v1\Validations;


use Backend\Modules\API\CRM\v1\Validation\HasEditUserProductCategoryValidator;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class ProductCategoryDeleteValidation extends Validation
{
    public function initialize()
    {
        $this->add(
            'category_id',
            new HasEditUserProductCategoryValidator(
                [
                    'message' => 'Недостаточно прав для сохранения',
                    'errorAttribute' => 'category_id',
                ]
            )
        );
        $this->add(
            'category_id',
            new PresenceOf(
                [
                    'message' => 'Не верный запрос',
                ]
            )
        );
        $this->setFilters('category_id','int');
    }
}
