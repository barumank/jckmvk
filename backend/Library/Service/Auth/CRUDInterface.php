<?php

namespace Backend\Library\Service\Auth;

/**
 * Interface CRUDInterface
 *
 * @author  Gubarev Sergey <sj.gubarev@gmail.com>
 *
 * @package App\Auth
 */
interface CRUDInterface
{
	public function saveFailedLogin($login, $reason);

	public function saveSuccessLogin($login);

	public function findUser($field);

	/**
	 * @param $key
	 * @param $token
	 * @param $data
	 *
	 * @return boolean
	 */
	public function setRememberData($key, $token, $data);

	/**
	 * @param $key
	 * @param $token
	 *
	 * @return array
	 */
	public function getRememberData($key, $token);

	/**
	 * @param string $key
	 * @param string $token
	 * @param int    $userId
	 *
	 * @return boolean
	 */
	public function setForgotData($key, $token, $userId);

	/**
	 * @param string $key
	 * @param string $token
	 *
	 * @return integer
	 */
	public function getForgotData($key, $token);
}