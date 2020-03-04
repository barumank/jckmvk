<?php

namespace Backend\Modules\Admin;

use Phalcon\Mvc\Router\Group;
use Phalcon\Text;

/**
 * Class Routes
 *
 * @package Backend\Modules\Admin
 */
class Routes extends Group
{
    public function initialize()
    {
        $this->setPrefix('/admin');

        $this->setPaths([
            'module'     => 'admin',
            'controller' => 'Index',
            'action'     => 'Index',
        ]);

        $this->add('');

        $this->add('/');

        $this->add('/:controller',
            [
                'controller' => 1,
            ])
            ->convert('controller', function ($controller) {
                return Text::camelize($controller);
            });

        $this->add('/:controller/:action',
            [
                'controller' => 1,
                'action'     => 2,
            ])
            ->convert('controller', function ($controller) {
                return Text::camelize($controller);
            })
            ->convert('action', function ($action) {
                return Text::camelize($action);
            });

        $this->add('/:controller/:action/:params',
            [
                'controller' => 1,
                'action'     => 2,
                'params'     => 3,
            ])
            ->convert('controller', function ($controller) {
                return Text::camelize($controller);
            })
            ->convert('action', function ($action) {
                return Text::camelize($action);
            });

        $this->addRoutes();
    }

    private function addRoutes()
    {

        $this->add('/login', [
            'controller' => 'User',
            'action'     => 'Login',
        ])->setName('admin.auth.login');

        $this->add('/logout', [
            'controller' => 'User',
            'action'     => 'Logout',
        ])->setName('admin.auth.logout');

        $this->add('/error', [
            'controller' => 'Index',
            'action'     => 'Error',
        ])->setName('admin.notfound');

    }
}