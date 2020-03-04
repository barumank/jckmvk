<?php
namespace Backend\Library\Service\CountryLocatorService\Library;

abstract class BaseLocator
{
    /**@var BaseLocator */
    protected $next;

    public function setNext(BaseLocator $handler)
    {
        $this->next = $handler;
    }

    public abstract function getCountryByIp($ip);
}