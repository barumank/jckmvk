<?php

namespace Backend\Modules\API\CRM\v1;

use Phalcon\Mvc\Router\Group;
use Phalcon\Text;

class Routes extends Group
{
	public function initialize()
	{
		$this->setPrefix('/api/crm/v1');

		$this->setPaths([
			'module'     => 'crm_api_v1',
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
	}
}