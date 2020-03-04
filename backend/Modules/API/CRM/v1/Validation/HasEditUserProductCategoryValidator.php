<?php


namespace Backend\Modules\API\CRM\v1\Validation;


use Backend\Models\MySQL\DAO\ProductCategory;
use Backend\Models\MySQL\DAO\User;
use Phalcon\Di\FactoryDefault;
use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;

class HasEditUserProductCategoryValidator extends Validator
{
    public function validate(Validation $validation, $attribute)
    {
        $value = $validation->getValue($attribute);
        $message = $this->getOption('message');
        $errorAttribute = $this->getOption('errorAttribute');
        if (empty($errorAttribute)) {
            $errorAttribute = $attribute;
        }
        if ($value === null) {
            return true;
        }
        $productCategory = ProductCategory::findFirst($value);
        if (empty($productCategory)) {
            $validation->appendMessage(new Message($message, $errorAttribute, 'error'));
            return false;
        }
        /**@var \Backend\Library\Service\Auth $auth */
        $auth = FactoryDefault::getDefault()->get('auth');
        if ($auth->getIdentity('role') !== User::ROLE_ADMIN
            && (int)$auth->getIdentity('user_id') !== (int)$productCategory->getUserId()) {
            $validation->appendMessage(new Message($message, $errorAttribute, 'error'));
            return false;
        }
        return true;
    }
}