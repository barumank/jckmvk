<?php


namespace Backend\Library;


class RequestException extends \Exception
{
    private $context = [];
    /**
     * @return mixed
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param mixed $context
     * @return RequestException
     */
    public function setContext($context)
    {
        $this->context = $context;
        return $this;
    }

}