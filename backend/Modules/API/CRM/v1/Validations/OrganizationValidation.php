<?php


namespace Backend\Modules\API\CRM\v1\Validations;


use Backend\Library\Phalcon\Validation\BuilderUniqueness;
use Backend\Library\Phalcon\Validation\CheckExistsValidator;
use Backend\Library\Phalcon\Validation\GetErrorMessages;
use Backend\Models\MySQL\DAO\Organization;
use Phalcon\Di\FactoryDefault;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;

class OrganizationValidation extends Validation
{
    use GetErrorMessages;

    public function initialize()
    {
        $this->add(
            'id',
            new PresenceOf(
                [
                    'message' => 'Организация не найдена',
                    'cancelOnFail' => true,
                ]
            )
        );
        $this->add(
            'id',
            new CheckExistsValidator(
                [
                    'message' => 'Организация не найдена',
                    'fieldName'=>'o.id',
                    'builder'=>Organization::getBuilder(),
                    'cancelOnFail' => true,
                ]
            )
        );
        $this->setFilters('id', 'int');


        $this->add(
            'name',
            new PresenceOf(
                [
                    'message' => '"Имя" обязательное поле',
                ]
            )
        );
        $this->setFilters('name', 'trim');

        $this->setFilters('inn', 'trim');
        $this->setFilters('legal_address', 'trim');
        $this->setFilters('postal_address', 'trim');
        $this->setFilters('correspondent_account', 'trim');
        $this->setFilters('payment_account', 'trim');
        $this->setFilters('name_director', 'trim');
        $this->setFilters('position_director', 'trim');
        $this->setFilters('by_virtue', 'trim');

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

        $this->setFilters('by_virtue', 'trim');

    }
}
