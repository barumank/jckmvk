<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 01.04.19
 * Time: 10:48
 */

namespace Backend\Modules\API\CRM\v1\Validations;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class UserImageValidation extends Validation
{
    public function initialize()
    {

        $this->add("image", new Validator\File([
            "maxSize" => "4M",
            "messageSize" => ":field exceeds the max filesize (:max)",
            "allowedTypes" => [
                "image/jpeg",
                "image/png",
            ],
            "messageType" => "Allowed file types are :types",
            "maxResolution" => "1920x1080",
            "messageMaxResolution" => "Max resolution of :field is :max",
        ]));

    }
}