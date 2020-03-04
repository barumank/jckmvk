<?php

if (!defined('BASE_DIR')) {
    define('BASE_DIR', dirname(__DIR__));
}

$connection = include __DIR__ . '/connection.php';

return new \Phalcon\Config([

    'application' => [
        'baseUri' => '/',
        'staticBaseUri' => '/',
        'modelsDir' => BASE_DIR . '/cache/',
        'timezone' => 'Europe/Minsk',
        'consolePath' => BASE_DIR . '/Modules/Console/cli.php',
        'environment' => $connection['environment'],
    ],
    'security' => [
        'ignoreUrlName' => [
            'site.auth.sign-up',
            'site.auth.login',
            'site.notfound',
            'user.referer',
            'partner.link',
            'media.redirectLink',
            'media.show',
            'site.landing'
        ]
    ],
    'database' => [
        'adapter' => 'Mysql',
        'charset' => 'utf8',
        'host' => $connection['db']['host'],
        'username' => $connection['db']['username'],
        'password' => $connection['db']['password'],
        'dbname' => $connection['db']['dbname'],
    ],
    'emailService' => [
        'host' => 'smtp.yandex.ru',
        'port' => 465,
        'SMTPAuth' => true,
        'username' => 'noreply@webempire.by',
        'password' => '####',
        'SMTPSecure' => 'ssl',
        'charSet' => 'utf-8',
        'fromUserName' => 'noreply',
        'fromEmail' => 'noreply@webempire.by',
        'toEmail' => 'dmitri.sharinski@gmail.com'
    ],
    'logger' => [
        'host' => $connection['logger']['host'],
        'port' => $connection['logger']['port']
    ],
    'redis' => [
        'path' => $connection['redis']['path'],
        'host' => $connection['redis']['host'],
        'port' => $connection['redis']['port'],
        'database' => $connection['redis']['database'],
    ],
    'session' => [
        'name' => 'sid',
        'host' => $connection['redis']['host'],
        'port' => $connection['redis']['port'],
        'prefix' => $connection['redis']['prefix']['session'],
        'index' => 0,
        'lifetime' => 60 * 60 * 8
    ],
    'modelsMetadata' => [
        'host' => $connection['redis']['host'],
        'port' => $connection['redis']['port'],
        'prefix' => $connection['redis']['prefix']['modelsMetadata'],
        'index' => 1,
    ],
    'modelsCache' => [
        'host' => $connection['redis']['host'],
        'port' => $connection['redis']['port'],
        'index' => 1,
        'prefix' => $connection['redis']['prefix']['modelsCache'],
        'lifetime' => 120,
    ],
    'settings' => [
        'prefix' => $connection['redis']['prefix']['settings'],
    ],
    'assetsWebpack' => [
        'path' => BASE_DIR . '/../frontend/public/crm/assets/assets.json',
    ],
    'bkApiService' => [
        'privateKey' => 'dfgecsksggdtt'
    ],
    'ajaxUploadService' => [
        'hashKey' => $connection['redis']['prefix']['ajaxUploadService'],
        'expire' => 1800,
        'webDir' => '/upload/cache',
        'cacheDir' => BASE_DIR . '/public/upload/cache',
    ],
    'webPath' => [
        'user' => '/upload/user',
    ],
    'dirs' => [
        'assets' => BASE_DIR . '/public/assets',
        'upload' => BASE_DIR . '/public/upload',
        'users' => BASE_DIR . '/public/upload/users',
        'backend' => BASE_DIR,
        'cache' => BASE_DIR . '/cache/',
        'cacheCategory' => BASE_DIR . '/cache/category',
        'log' => BASE_DIR . '/log/',
    ],
]);
