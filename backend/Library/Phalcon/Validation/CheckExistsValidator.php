<?php


namespace Backend\Library\Phalcon\Validation;


use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;

class CheckExistsValidator extends Validator
{
    public function validate(Validation $validation, $attribute)
    {

        $value = $validation->getValue($attribute);
        $fieldName = $this->getOption('fieldName');
        $message = $this->getOption('message');
        $code = $this->getOption('code');

        /**@var \Phalcon\Mvc\Model\Query\BuilderInterface $builder */
        $builder = $this->getOption('builder');

        $builder->where("{$fieldName} = :value:", [
            'value' => $value,
        ]);

        $query = $builder->getQuery()->execute();
        if (empty($query->count())) {
            $validation->appendMessage(new Message($message, $attribute, 'error', $code));
            return false;
        }
        return true;
    }
}