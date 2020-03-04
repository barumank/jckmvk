<?php

namespace Backend\Library\Phalcon\Db;
/**
 * Class MysqlAdapter
 *
 * @author Pavlovskiy Artem <tema23p@gmail.com>
 *
 */
class MysqlAdapter extends \Phalcon\Db\Adapter\Pdo\Mysql
{

	public function ping()
	{
		$levelError = error_reporting();
		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
		try {
			$this->fetchAll('SELECT 1');
		} catch ( \Exception  $e ) {
			$this->connect();
		}
		error_reporting($levelError);

		return $this;
	}
}