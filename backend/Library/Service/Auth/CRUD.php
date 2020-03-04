<?php

namespace Backend\Library\Service\Auth;
use Phalcon\Mvc\User\Component;

/**
 * Class CRUD
 *
 * @author  Gubarev Sergey <sj.gubarev@gmail.com>
 *
 * @package App\Auth
 * @property \Redis $redis
 */
class CRUD extends Component implements CRUDInterface
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * CRUD constructor.
     * @param string $userClass
     */
    public function __construct(string $userClass)
    {
       $user = new $userClass();

       if ($user instanceof UserInterface){
           $this->user = $user;
       }
    }

    public function saveFailedLogin($login, $reason)
	{
	}

	public function saveSuccessLogin($login)
	{
	}

	public function findUser($value)
	{
		return $this->user->findUserByLogin($value);
	}

    public function findUserById($id)
    {
        return $this->user->findUserById($id);
    }

	public function setRememberData($key, $token, $data)
	{
		return $this->redis->hSet($key, $token, json_encode($data, JSON_UNESCAPED_UNICODE)) !== false;
	}

	public function getRememberData($key, $token)
	{
		return json_decode($this->redis->hGet($key, $token), true);
	}

	/**
	 * @param string $key
	 * @param string $token
	 * @param int    $userId
	 *
	 * @return mixed
	 */
	public function setForgotData($key, $token, $userId)
	{
		return $this->redis->hSet($key, $token, $userId) !== false;
	}

	/**
	 * @param string $key
	 * @param string $token
	 *
	 * @return int
	 */
	public function getForgotData($key, $token)
	{
		return (int) $this->redis->hGet($key, $token);
	}
}