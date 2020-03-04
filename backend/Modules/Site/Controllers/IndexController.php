<?php

namespace Backend\Modules\Site\Controllers;

use Backend\Modules\Site\Controller;
use Phalcon\Mvc\View;

class IndexController extends Controller
{
    public function IndexAction()
    {

    }

    public function ErrorAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->response->setHeader('404', 'Page not fount');
    }

}
