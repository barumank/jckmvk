<?php

namespace Backend\Library\Annotations;

use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Component;

/**
 * Class ACL
 *
 * @author  Gubarev Sergey <sj.gubarev@gmail.com>
 *
 * @package Backend\Library\Annotations
 * @property \Backend\Library\Service\Auth auth
 */
class ACL extends Component
{
	
	private $noAuth = false;

	private $aclError =false;


    /**
	 * @param Dispatcher $dispatcher
	 *
	 * @return bool
	 */
	public function check(Dispatcher $dispatcher)
	{
	    $this->noAuth = false;
	    $this->aclError = false;
	    $annotations = $this->annotations->getMethod(
			$dispatcher->getControllerClass(),
			$dispatcher->getActiveMethod()
		);

		if ( $annotations->has('AclRoles') ) {
			$annotation = $annotations->get('AclRoles');
			$arguments  = $annotation->getArguments();

			$roles = array_combine($arguments, $arguments);

			if(array_key_exists('guest', $roles)){
			    return true;
            }

			if ($this->auth->isIdentity() === false ) {
                $this->noAuth = true;
                return false;
			}

			$userRole = $this->auth->getUser()->getRole();
			if ( !array_key_exists($userRole, $roles) ) {
			    $this->aclError = true;
				return false;
			}
		}

		if($this->auth->isIdentity() === false){
		    $this->noAuth = true;
		    return false;
        }
		return true;
	}

    public function hasNoAuth()
    {
        return $this->noAuth;
	}

    public function hasAclError()
    {
        return $this->aclError;
	}
}