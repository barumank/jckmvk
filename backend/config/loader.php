<?php

/**
 * Autoloader
 */
$loader = new \Phalcon\Loader();

$loader->registerNamespaces([
	'Backend' => BASE_DIR,
])->register();

$composerAutoloader = BASE_DIR . '/vendor/autoload.php';

if ( file_exists($composerAutoloader) ) {
	include $composerAutoloader;
}