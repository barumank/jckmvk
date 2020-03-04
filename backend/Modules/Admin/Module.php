<?php

namespace Backend\Modules\Admin;

use Backend\Library\Service\Auth;
use Backend\Library\Transliterator;
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
            $dispatcher->setDefaultNamespace('Backend\Modules\Admin\Controllers');
            $dispatcher->setEventsManager($eventsManager);

            return $dispatcher;
        }, true);

        /**
         * Setting up the view component
         */
        $di->set('view', function () {

            $view = new View();
            $view->setDI($this);
            $view
                ->setViewsDir(__DIR__ . '/views/')
                ->setPartialsDir(__DIR__ . '/views/partials/');

            $view->registerEngines([
                '.volt' => 'voltShared',
            ]);

            return $view;
        }, true);

        $di->set('auth', function () {
            return new \Backend\Library\Service\Auth(
                \Backend\Models\MySQL\DAO\User::class,
                ['session_key'=>'identityAdmin']
            );
        }, true);

        $di->set('paginationService', function () {
            return new \Backend\Library\Service\PaginationService\Manager('pagination');
        }, true);

        $di->set('transliterator', function () {
            return new Transliterator();
        }, true);

        $di->set('flashSession', function () {
            return new \Phalcon\Flash\Session([
                'error' => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice' => 'alert alert-info',
                'warning' => 'alert alert-warning',
            ]);

        }, true);

        $di->set('emailUnloadingService', function () {
            return new \Backend\Library\Service\EmailUnloadingService\Manager();
        }, true);

        $di->set('jobService', function () {
            return new \Backend\Library\Service\JobService\Manager();
        }, true);

        $di->set('progressTaskService', function () {
            return new \Backend\Library\Service\ProgressTaskService\Manager();
        }, true);

        $di->set('partnerFeedbackService', function () {
            return new \Backend\Library\Service\PartnerFeedbackService\Manager();
        }, true);

    }
}