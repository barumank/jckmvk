<?php

namespace Backend\Modules\Admin;

use Backend\Library\Annotations\ACL;
use Backend\Library\Service\Auth;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Text;

/**
 * Class Security
 * @package Backend\Modules\Admin
 * @property Auth $auth
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

        $routeName = $this->router->getMatchedRoute()->getName();

        if (!$this->auth->isIdentity() && ($routeName !== 'admin.auth.login' && $routeName !== 'admin.notfound')) {
            $this->response->redirect($this->url->get(['for'=>'admin.auth.login']));
            return false;
        }

        if($this->auth->isIdentity() && !in_array($this->auth->getIdentity('role'),['admin'])){
            $this->response->redirect('/',true);
            return false;
        }

        return true;
    }

    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {

        $ctrlName = $dispatcher->getControllerName();
        $normalizedName = Text::camelize($ctrlName);
        $dispatcher->setControllerName($normalizedName);

        $acl = new ACL();

        if (false === $acl->check($dispatcher)) {
            $dispatcher->forward([
                'controller' => 'Index',
                'action'     => 'Error',
            ]);

            return false;
        }

        return true;
    }

    /** * До вызова диспетчером любого исключения
     *
     * @param Event      $event
     * @param Dispatcher $dispatcher
     * @param \Exception $exception
     *
     * @return bool
     */
    public function beforeException(Event $event, Dispatcher $dispatcher, $exception)
    {
        switch ($exception->getCode()) {
            case Dispatcher::EXCEPTION_ACTION_NOT_FOUND :
            case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND :
                $dispatcher->forward([
                    'controller' => 'Index',
                    'action'     => 'Error'
                ]);

                return false;
        }

        return true;
    }
}