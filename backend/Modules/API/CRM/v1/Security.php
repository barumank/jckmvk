<?php

namespace Backend\Modules\API\CRM\v1;

use Backend\Library\Annotations\ACL;
use Backend\Library\Service\Auth;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;

/**
 * Class Security
 * @package Backend\Modules\API\Admin\v1
 * @property Auth $auth
 * @property \Backend\Library\Service\JsonResponse\Manager jsonResponse
 */
class Security extends Plugin
{

    /**
     * Срабатывает перед входом в цикл диспетчера.
     * В этот момент диспетчер не знает, существуют ли контроллеры или действия, которые должны быть выполнены.
     * Диспетчер владеет только информацией поступившей из маршрутизатора
     * *
     * Прерывает операцию - Да
     * Triggered on - Listeners
     *
     * @param Event      $event
     * @param Dispatcher $dispatcher
     *
     * @return bool
     */
    public function beforeDispatchLoop(Event $event, Dispatcher $dispatcher)
    {
        $ctrlName = $dispatcher->getControllerName();
        $normalizedName = strtolower(preg_replace('/(?<!^)([A-Z])/', '-\1' ,$ctrlName));
        $dispatcher->setControllerName($normalizedName);

        return true;
    }

    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
	{
		$acl = new ACL();

        if (false === $acl->check($dispatcher)) {
            if($acl->hasNoAuth()){
                $dispatcher->forward([
                    'controller' => 'Auth',
                    'action'     => 'Error',
                ]);
                return false;
            }
            if($acl->hasAclError()){
                $dispatcher->forward([
                    'controller' => 'Auth',
                    'action'     => 'AclError',
                ]);
                return false;
            }
        }
		return true;
	}
}