<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 13.03.19
 * Time: 14:18
 */

namespace Backend\Library\Traits;
use Phalcon\Validation\Message;

trait AddErrorToForm
{
    public function appendError($message, $field, $type = null)
    {
        if ( empty($this->_messages) ) {
            $this->_messages = new Message\Group();
        }

        $message = new Message($message, $field, $type);

        $this->_messages->appendMessage($message);
        $this->get($field)->appendMessage($message);
    }

}