<?php


namespace Backend\Modules\API\CRM\v1\Validations;


use Backend\Modules\API\CRM\v1\Validation\HasEditUserProductCategoryValidator;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class ProductCategoryValidation extends Validation
{
    public function initialize()
    {
        $this->add(
            'id',
            new HasEditUserProductCategoryValidator(
                [
                    'message' => 'Недостаточно прав для сохранения',
                    'errorAttribute' => 'name',
                ]
            )
        );
        $this->add(
            'name',
            new PresenceOf(
                [
                    'message' => '"Название" обязательное поле',
                ]
            )
        );
        
        $this->setFilters('id','int');
        $this->setFilters('parent_id','int');
        $this->setFilters('type','int');
    }
}
