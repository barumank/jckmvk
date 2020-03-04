<?php

namespace Backend;
use Phalcon\DiInterface;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\Router;

class App
{
	/**
	 * @var \Phalcon\DiInterface
	 */
	private $_di;

	/**
	 * @var \Phalcon\Mvc\Application
	 */
	private $_application;

	/**
	 * @var \Phalcon\Config
	 */
	private $_config;

	/**
	 * App constructor.
	 *
	 * @param $_dependencyInjector
	 */
	public function __construct(DiInterface $_dependencyInjector)
	{
		$this->_di = $_dependencyInjector;

		$this->_config = $_dependencyInjector->get('config');

		$this->createPhalconApplication();
		$this->registerRoutes();
		$this->registerServices();
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->_application->handle()->getContent();
	}

	/**
	 * @return \Phalcon\Mvc\Application
	 */
	private function createPhalconApplication()
	{
		/**
		 * Приложение
		 */
		$this->_application = new Application($this->_di);


		return $this->_application;
	}

	private function registerRoutes()
	{

        /**
         * Регистрация установленных модулей
         */
        $this->_application->registerModules([
            'admin' => [
                'className' => \Backend\Modules\Admin\Module::class,
            ],
            'site'  => [
                'className' => \Backend\Modules\Site\Module::class,
            ],
            'crm_api_v1'   => [
                'className' => \Backend\Modules\API\CRM\v1\Module::class,
            ],

        ]);

	    /**
		 * Registering a router
		 */
		$this->_di->set('router', function () {
			$router = new Router(false);
			$router
				->removeExtraSlashes(true)
				->mount(new \Backend\Modules\Site\Routes())
				->mount(new \Backend\Modules\Admin\Routes())
				->mount(new \Backend\Modules\API\CRM\v1\Routes());

            $router->setDefaultModule('site');
			$router->notFound([
				'controller' => 'Index',
				'action'     => 'Error',
			]);

			return $router;
		},true);
	}

	private function registerServices()
	{

	}
}