<?php

namespace Backend\Modules\API\CRM\v1;

use Phalcon\Http\Request;
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
             * обработка сырого json поста
             */
			$eventsManager->attach('dispatch:afterBinding',function ($event,$dispatcher){
                /**@var Request $request*/
                $request = $this->get('request');
                $contentType = $request->getHeader('Content-Type');
                if (strpos($contentType, 'application/json') !== false) {
                    $rawBody = $request->getJsonRawBody(true);
                    if ($request->isPost()) {
                        foreach ($rawBody as $key => $value) {
                            $_POST[$key] = $value;
                        }
                    }
                }
            });
			/**
			 * Слушаем события возникающие в диспетчере, используя плагин Security
			 */
			$eventsManager->attach('dispatch', new Security($this));

			$dispatcher = new Dispatcher();
			$dispatcher->setDefaultNamespace('\Backend\Modules\API\CRM\v1\Controllers');
			$dispatcher->setEventsManager($eventsManager);

			return $dispatcher;
		}, true);

        $di->set('paginationService', function () {
            return new \Backend\Library\Service\PaginationService\JsonManager();
        },true);

		/**
		 * Setting up the view component
		 */
		$di->set('view', function () {
			$view = new View();
			$view->setDI($this);
			$view->registerEngines([
				'.volt' => 'voltShared',
			]);
			return $view;
		},true);
	}
}
