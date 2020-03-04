<?php


namespace Backend\Library\Phalcon\Validation;
use \Phalcon\Validation\Message\Group;
/**
 * Trait GetFirstMessage
 * @package Backend\Library\Phalcon\Validation
 */
trait GetErrorMessages
{

    public function getMessage($priorityFields=[]):string
    {
        $messages = $this->getMessages();
        if(empty($messages->count())){
            return '';
        }
        if(!empty($priorityFields)){
            $priorityFields = array_flip($priorityFields);
            foreach ($messages as $message){
                if(isset($priorityFields[$message->getField()])){
                    return  $message->getMessage();
                }
            }
        }
        $messages->rewind();
        return $messages->current()->getMessage();
    }

    public function getMessages($excludeFields=[]):Group
    {
        $messages =  parent::getMessages();
        if(empty($excludeFields)){
            return $messages;
        }
        $group = new Group();
        foreach ($messages as $message){
            if(!isset($excludeFields[$message->getField()])){
                $group->appendMessage($message);
            }
        }
        return $group;
    }
}