<?php

namespace Backend\Modules\Site;

use Backend\Library\Breadcrumbs;
use Backend\Library\SEO\SEO;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\View;

class Module implements ModuleDefinitionInterface
{
    /**
     * Registers an autoloader related to the module
     *
     * @param mixed $dependencyInjector
     */
    public function registerAutoloaders(\Phalcon\DiInterface $dependencyInjector = null)
    {
    }

    /**
     * Registers services related to the module
     *
     * @param mixed $di
     */
    public function registerServices(\Phalcon\DiInterface $di)
    {
        /**
         * Регистрация диспетчера
         */
        $di->set('dispatcher', function () {

            /**
             * @var \Phalcon\Events\Manager $eventsManager
             */
            $eventsManager = $this->get('eventsManager');

            /**
             * Слушаем события возникающие в диспетчере, используя плагин Security
             */
            $eventsManager->attach('dispatch', new Security($this));

            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('Backend\Modules\Site\Controllers');
            $dispatcher->setEventsManager($eventsManager);

            return $dispatcher;
        }, true);

        /**
         * Setting up the view component
         */
        $di->set('view', function () use ($di) {

            $view = new View();
            $view->setDI($di);
            $view
                ->setViewsDir(__DIR__ . '/views/')
                ->setPartialsDir(__DIR__ . '/views/partials/');
            $view->registerEngines([
                '.volt' => 'voltShared',
            ]);

            return $view;
        }, true);


    }
}
