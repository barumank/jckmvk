<?php

namespace Backend\Modules\Site;

use Phalcon\Mvc\Router\Group;
use Phalcon\Text;

class Routes extends Group
{
    public function initialize()
    {
        $this->setPaths([
            'module' => 'site',
            'controller' => 'Index',
            'action' => 'Index',
        ]);
        $this->add('');
        $this->add('/');
        $this->add('/[A-z\-\.\_/]+',[
            'controller' => 'Index',
            'action' => 'Index',
        ]);

        $this->addRoutes();
    }

    public function addRoutes()
    {

    }

}
