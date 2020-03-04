<?php

use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Loader;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', '0');
date_default_timezone_set('Europe/Minsk');

$mem_start_test = memory_get_usage();

define('BASE_DIR', dirname(dirname(__DIR__)));

// Используем стандартный для CLI контейнер зависимостей
require BASE_DIR . '/Modules/Console/config/services.php';

/**
 * Регистрируем автозагрузчик, и скажем ему, чтобы зарегистрировал каталог задач
 */
$loader = new Loader();
$loader->registerNamespaces([
	'Backend' => BASE_DIR,
])->register();

$composerAutoloader = BASE_DIR . '/vendor/autoload.php';
if ( file_exists($composerAutoloader) ) {
    require $composerAutoloader;
}


// Создаем консольное приложение
$console = new ConsoleApp();
$console->setDI($di);

$di->setShared('console',$console);


/**
 * Определяем консольные аргументы
 */
$arguments = [];

foreach ( $argv as $k => $arg ) {
	if ( $k === 1 ) {
		$arguments['task'] = '\Backend\Modules\Console\Tasks\\' . $arg;
	} elseif ( $k === 2 ) {
		$arguments['action'] = $arg;
	} elseif ( $k >= 3 ) {
		$arguments['params'][] = $arg;
	}
}

try {
	// обрабатываем входящие аргументы
	$console->handle($arguments);
} catch ( \Phalcon\Exception $e ) {
	echo $e->getMessage();
	exit(255);
}

