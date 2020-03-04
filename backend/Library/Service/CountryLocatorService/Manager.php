<?php
namespace Backend\Library\Service\CountryLocatorService;

use Backend\Library\Service\CountryLocatorService\Library\BaseLocator;
use Backend\Library\Service\CountryLocatorService\Library\GeopluginLocator;
use Backend\Library\Service\CountryLocatorService\Library\IpApiIoLocator;

class Manager
{
    /**
     * @var BaseLocator
     */
    protected $locator;
    public function __construct()
    {
        $this->locator = new IpApiIoLocator();
        $geopluginLocator = new GeopluginLocator();
        $this->locator->setNext($geopluginLocator);

    }

    public function getCountryByIp($ip)
    {
        return $this->locator->getCountryByIP($ip);
    }
}