<?php

namespace Backend\Modules\Admin;

use Backend\Library\Service\Auth;

/**
 * Class Controller
 *
 * @package Backend\Modules\Admin
 *
 * @property Auth $auth
 * @property \Backend\Library\Service\SettingsService\Manager $settingsService
 * @property \Phalcon\Cache\Backend\Redis $modelsCache
 * @property \Backend\Library\Service\PaginationService\Manager $paginationService
 * @property \Backend\Library\Service\JsonResponse\Manager $jsonResponse
 * @property \Backend\Library\Service\StatisticService\Manager $statisticService
 * @property \Backend\Library\Service\BkApiService\Manager bkApiService
 */
class Controller extends \Phalcon\Mvc\Controller
{

    public function initialize()
    {

        $this->assets->addCss('/admin/vendors/bootstrap/dist/css/bootstrap.min.css')
            ->addCss('/admin/vendors/font-awesome/css/font-awesome.min.css')
            ->addCss('/admin/vendors/nprogress/nprogress.css')
            ->addCss('/admin/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css')
            /** ADMIN JS **/
            ->addJs('/admin/vendors/jquery/dist/jquery.min.js')
            ->addJs('/admin/vendors/bootstrap/dist/js/bootstrap.min.js')
            ->addJs('/admin/vendors/fastclick/lib/fastclick.js')
            ->addJs('/admin/vendors/nprogress/nprogress.js')
            ->addJs('/admin/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js')
            ->addJs('/admin/js/main.js')
            /*notify*/
            ->addCss('/admin/vendors/pnotify/dist/pnotify.css')
            ->addCss('/admin/vendors/pnotify/dist/pnotify.buttons.css')
            ->addCss('/admin/vendors/pnotify/dist/pnotify.nonblock.css')
            ->addJs('/admin/vendors/pnotify/dist/pnotify.js')
            ->addJs('/admin/vendors/pnotify/dist/pnotify.buttons.js')
            ->addJs('/admin/vendors/pnotify/dist/pnotify.nonblock.js')
            /*notify*/;
            /** END ADMIN JS **/


            /** FOOTER JS **/
        $this->assets->collection('footerJs')
            ->addJs('/admin/js/custom.js');

        /**LAST CSS**/
        $this->assets->collection('lastCss')
            ->addCss('/admin/css/custom.css')
            ->addCss('/admin/css/admin.css');
    }

    /**
     * @return bool
     */
    protected function notFound()
    {
        $this->dispatcher->forward([
            'controller' => 'Index',
            'action' => 'Error',
        ]);

        return false;
    }
}