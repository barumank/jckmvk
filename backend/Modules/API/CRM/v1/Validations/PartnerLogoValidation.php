<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 25.03.19
 * Time: 11:23
 */

namespace Backend\Modules\API\CRM\v1\Validations;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class PartnerLogoValidation extends Validation
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
