<?php

namespace Backend\Library\Service\JsonResponse;

use Phalcon\Mvc\User\Component;
use Phalcon\Validation\Message\Group;

class Manager extends Component
{

    /**
     * @param null $data
     * @param string $status
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function sendSuccess($data = null)
    {
        return $this->response->setJsonContent([
            'status'=>'ok',
            'data' => $data,
        ], JSON_UNESCAPED_UNICODE)
            ->send();
    }

    /**
     * @param string $messageError
     * @param array $messages
     * @param string $title
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function sendError($messageError = '', $messages = [])
    {
        $errors = [];
        if ($messages instanceof Group) {
            foreach ($messages as $message) {
                $errors[$message->getField()] = $message->getMessage();
            }
        } else {
            $errors = $messages;
        }
        return $this->response->setJsonContent([
            'status'=>'error',
            'error' => $messageError,
            'errors' => $errors,
        ], JSON_UNESCAPED_UNICODE)
            ->send();
    }

}
