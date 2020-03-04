<?php


namespace Backend\Library\Phalcon\Validation;

use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;

class ReCaptchaValidator extends Validator
{
    public function validate(Validation $validation, $attribute)
    {

        $value = $validation->getValue($attribute);
        $secretKey = $this->getOption('secret_key');
        $ip = $validation->request->getClientAddress();

        if (!$this->verify($value, $ip,$secretKey)) {
            $validation->appendMessage(new Message($this->getOption('message'), $attribute, 'Recaptcha'));
            return false;
        }
        return true;
    }

    protected function verify($value, $ip,$secretKey)
    {
        $params = [
            'secret' => $secretKey,
            'response' => $value,
            'remoteip' => $ip
        ];
        $response = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?' . http_build_query($params)));

        return (bool)$response->success;
    }
}