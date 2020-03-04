<?php

namespace Backend\Modules\Site;

use Backend\Library\Breadcrumbs;
use Backend\Library\SEO\SEO;
use Backend\Library\Service\Auth;

/**
 * Class Controller
 * @property Auth $auth
 * @package Backend\Modules\Site
 * @property Seo $seo
 * @property Breadcrumbs $breadcrumbs
 * @property \Backend\Library\Service\SettingsService\Manager $settingsService
 * @property \Backend\Library\Service\RefererService\Manager refererService
 * @property \Backend\Library\Service\JsonResponse\Manager $jsonResponse
 * @property \Backend\Library\Service\PaginationService\Manager $paginationService
 * @property \Backend\Library\Service\StatisticService\Manager statisticService
 * @property \Backend\Library\Service\CountryLocatorService\Manager countryLocatorService
 * @property \Backend\Library\Service\EmailService\Manager emailService
 */
class Controller extends \Phalcon\Mvc\Controller
{

    public function initialize()
    {

    }
}
