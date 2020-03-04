<?php


namespace Backend\Library\Phalcon\Validation;

use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;

class BuilderUniqueness extends Validator
{
    public function validate(Validation $validation, $attribute)
    {

        $value = $validation->getValue($attribute);
        $excludeField = $this->getOption('excludeField');
        $excludeValue = $this->getOption('excludeValue');
        $fieldName = $this->getOption('fieldName');
        $message = $this->getOption('message');
        /**@var \Phalcon\Mvc\Model\Query\BuilderInterface $builder */
        $builder = $this->getOption('builder');


        $builder->where("{$fieldName} = :value:", [
            'value' => $value,
        ]);
        if (!empty($excludeField)) {
            $builder->andWhere("{$excludeField} != :excludeValue:", [
                'excludeValue' => $excludeValue
            ]);
        }
        $query = $builder->getQuery()->execute();
        if (empty($query->count())) {
            return true;
        }
        $validation->appendMessage(new Message($message, $attribute, 'error'));
        return false;
    }
}