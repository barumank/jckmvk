<?php


$di = new \Phalcon\Di\FactoryDefault\Cli();

$di->setShared('config', function () {
    return require dirname(__DIR__) . '/../../config/config.php';
});


/**
 * Общий экземпляр Volt
 */
$di->set('voltShared', function (\Phalcon\Mvc\View $view) {
    $config = $this->get('config');

    $view->setMainView('layout');

    $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $this);
    $volt->setOptions([
        'compiledPath' => $config->dirs->cache,
        'compiledSeparator' => '_',
    ]);
    $compiler = $volt->getCompiler();
    $compiler->addFunction('strtotime', 'strtotime');
    $compiler->addFunction('in_array', 'in_array');

    return $volt;
}, true);

$di->set('view', function () {

    $view = new \Phalcon\Mvc\View();
    $view->setDI($this);
    $view
        ->setViewsDir(__DIR__ . '/../views/')
        ->setPartialsDir(__DIR__ . '/../views/');
    $view->registerEngines([
        '.volt' => 'voltShared',
    ]);

    return $view;
}, true);

/**
 * Session
 */
$di->set('session', function () {
    $config = $this->getConfig();
    $session = new \Phalcon\Session\Adapter\Redis([
        'host' => $config->session->host,
        'port' => $config->session->port,
        'prefix' => $config->session->prefix,
        'index' => $config->session->index,
        'lifetime' => $config->session->lifetime,
    ]);
    return $session;
}, false);

/**
 * Redis
 */
$di->set('redis', function () {
    $config = $this->getConfig();

    $Redis = new \Redis();
    $Redis->connect($config->redis->host, $config->redis->port);
    $Redis->select($config->redis->database);

    return $Redis;
}, true);

/**
 * Database
 */
$di->set('db', function () {

    /**@var \Phalcon\Events\Manager $eventsManager */
    $eventsManager = $this->get('eventsManager');
    $connection = new \Backend\Library\Phalcon\Db\MysqlAdapter((array)$this->getConfig()->database);
    $connection->setEventsManager($eventsManager);

    return $connection;
}, true);
/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function () {
    $config = $this->getConfig();
    return new \Phalcon\Mvc\Model\MetaData\Redis([
        'host' => $config->redis->host,
        'port' => $config->redis->port,
        'prefix' => $config->modelsMetadata->prefix,
        'index' => $config->modelsMetadata->index,
    ]);
}, true);

/**
 * Cache
 */
$di->set('modelsCache', function () {
    $config = $this->getConfig();
    $frontCache = new \Phalcon\Cache\Frontend\Data([
        'lifetime' => $config->modelsCache->lifetime,
    ]);

    return new \Phalcon\Cache\Multiple([
        new \Phalcon\Cache\Backend\Memory($frontCache),
        new \Phalcon\Cache\Backend\Redis($frontCache, [
            'host' => $config->modelsCache->host,
            'port' => $config->modelsCache->port,
            'index' => $config->modelsCache->index,
            'prefix' => $config->modelsCache->prefix,
        ])
    ]);

}, true);

$di->set('crypt', function () {
    $crypt = new \Phalcon\Crypt();
    $crypt->setKey('76.nsh6ine56byq5bzi,e');

    return $crypt;
}, true);


$di->set('emailService', function () {
    $config = $this->getConfig();
    return new \Backend\Library\Service\EmailService\Manager($config->emailService);
}, true);

$di->set('logger', function () {
    //return new \Phalcon\Logger\Adapter\Stream("php://stderr");
    $config = (array)$this->get('config')->logger;
    return new \Backend\Library\Phalcon\Logger\TcpLogger('console',$config);
}, true);
$di->set('loggerFactory', function () {
    //return new \Phalcon\Logger\Adapter\Stream("php://stderr");
    $config = (array)$this->get('config')->logger;
    return new \Backend\Library\Phalcon\Logger\TcpLogger('console',$config);
});
$di->set('fileService', function () {
    return new \Backend\Library\Service\FileService\Manager();
}, true);

$di->set('categoryService', function () {
    return new \Backend\Library\Service\CategoryService\Manager();
}, true);

