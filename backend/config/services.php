<?php

$di = new \Phalcon\DI\FactoryDefault();

$di->setShared('config', function () {
    return require __DIR__ . '/config.php';
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () {
    $config = $this->getConfig();

    $url = new \Phalcon\Mvc\Url();
    $url->setBaseUri($config->application->baseUri);
    $url->setStaticBaseUri($config->application->staticBaseUri);

    return $url;
}, true);

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
 * Security
 */
$di->set('security', function () {
    $security = new \Phalcon\Security();
    $security->setWorkFactor(8);

    return $security;
}, true);

/**
 * Session
 */
$di->set('session', function () {
    $config = $this->getConfig();
    session_name($config->session->name);
    $session = new \Phalcon\Session\Adapter\Redis([
        'host' => $config->session->host,
        'port' => $config->session->port,
        'prefix' => $config->session->prefix,
        'index' => $config->session->index,
        'lifetime' => $config->session->lifetime,
    ]);

    $session->start();

    return $session;
}, true);

/**
 * DB Profiler
 */
$di->set('profiler', function () {
    return new \Phalcon\Db\Profiler();
}, true);

/**
 * Database
 */
$di->set('db', function () {

    /**
     * @var \Phalcon\Events\Manager $eventsManager
     */
    $eventsManager = $this->get('eventsManager');

    $connection = new \Backend\Library\Phalcon\Db\MysqlAdapter((array)$this->getConfig()->database);

    $connection->setEventsManager($eventsManager);

    /**
     * @var \Phalcon\Db\Profiler $profiler
     */
    $profiler = $this->get('profiler');

    $eventsManager->attach('db', function (\Phalcon\Events\Event $event, \Phalcon\Db\Adapter $connection) use ($profiler) {
        if ($event->getType() === 'beforeQuery') {
            $profiler->startProfile($connection->getSQLStatement());
        }

        if ($event->getType() === 'afterQuery') {
            $profiler->stopProfile();
        }
    });

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

$di->setShared('filter', function () {

    $filter = new \Phalcon\Filter();

    $filter->add('CKEditorSpaceFilter', new \Backend\Library\Phalcon\Filter\CKEditorSpaceFilter());

    $filter->add('MoneyFilter', new \Backend\Library\Phalcon\Filter\MoneyFilter());

    return $filter;

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

    $view->setVar('year', date('Y'));
    $compiler = $volt->getCompiler();
    $compiler->addFunction('strtotime', 'strtotime');
    $compiler->addFunction('in_array', 'in_array');

    return $volt;
}, true);

$di->set('assets', function () {
    $config = $this->get('config');
    return (new \Backend\Library\WebpackAssets([
        'path' => $config->assetsWebpack->path,
    ]))
        ->addJs('main')
        ->addCss('main');
}, true);

$di->set('t', function () {
    return new \Phalcon\Translate\Adapter\NativeArray([
        'content' => [],
    ]);
}, true);

$di->set('auth', function () {
    return new Backend\Library\Service\Auth(\Backend\Models\MySQL\DAO\User::class);
}, true);

$di->set('settingsService', function () {
    $config = $this->getConfig();
    return new \Backend\Library\Service\SettingsService\Manager($config->settings->prefix);
}, true);

/**
 * Преднозначен для загрузки файлов
 */
$di->set('ajaxUploadService', function () {
    $config = $this->get('config')->ajaxUploadService;
    return new \Backend\Library\Service\AjaxUploadService\Manager($config->hashKey, $config->expire);
}, true);

$di->set('jsonResponse', function () {
    return new \Backend\Library\Service\JsonResponse\Manager();
}, true);

$di->set('statisticService', function () {
    return new \Backend\Library\Service\StatisticService\Manager();
}, true);


$di->set('logger', function () {
    //return new \Phalcon\Logger\Adapter\Stream("php://stderr");
    $config = (array)$this->get('config')->logger;
    return new \Backend\Library\Phalcon\Logger\TcpLogger('web', $config);
}, true);

$di->set('loggerFactory', function () {
    //return new \Phalcon\Logger\Adapter\Stream("php://stderr");
    $config = (array)$this->get('config')->logger;
    return new \Backend\Library\Phalcon\Logger\TcpLogger('console', $config);
});


$di->set('refererService', function () {
    $config = (array)$this->get('config')->refererService;
    return new \Backend\Library\Service\RefererService\Manager($config);
}, true);

$di->set('countryLocatorService', function () {
    return new \Backend\Library\Service\CountryLocatorService\Manager();
}, true);

$di->set('emailService', function () {
    $config = $this->getConfig();
    return new \Backend\Library\Service\EmailService\Manager($config->emailService);
}, true);


$di->set('fileService', function () {
    return new \Backend\Library\Service\FileService\Manager();
}, true);

$di->set('requestHelperService', function () {
    return new \Backend\Library\Service\RequestHelperService\RequestHelper();
}, true);

$di->set('categoryService', function () {
    return new \Backend\Library\Service\CategoryService\Manager();
}, true);

$di->set('productService', function () {
    return new \Backend\Library\Service\ProductService\Manager();
}, true);
