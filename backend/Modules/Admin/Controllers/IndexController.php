<?php

namespace Backend\Modules\Admin\Controllers;

use Backend\Library\Service\StatisticService\Library\OrderStatusLabelClassBuilder;
use Backend\Modules\Admin\Controller;
use Phalcon\Mvc\View;

class IndexController extends Controller
{

    public function IndexAction()
    {
        $this->assets
            ->addCss('/admin/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')
            ->addJs('/admin/vendors/moment/min/moment.min.js')
            ->addJs('/admin/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')
            ->addJs('/admin/vendors/echarts/dist/echarts.min.js')
            ->addJs('/admin/js/home.js');

        $dateStart = $this->request->getQuery('date_start', 'string', date('Y-m-d'));
        $dateEnd = $this->request->getQuery('date_end', 'string', date('Y-m-d'));
        $recentPage = $this->request->getQuery('recent_page', 'int', 1);

        $builderFilter = $this->statisticService->getDashboardFilter();
        $builderFilter->setDateFrom($dateStart)
            ->setDateTo($dateEnd);

        $this->view->setVars([
            'builderFilter' => $builderFilter,
            'recentPage' => $recentPage,
            'statusBuilder' => new OrderStatusLabelClassBuilder(),
            'timeAgo' => new \Westsworld\TimeAgo()
        ]);
    }

    public function ErrorAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->response->setHeader('404', 'Page not fount');
    }
}