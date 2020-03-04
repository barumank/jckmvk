<?php

return [
    'db' => [
        'host' => 'mysql',
        'username' => 'root',
        'password' => 'testdbmysqlpass',
        'dbname' => 'mvk_crm',
    ],
    'redis' => [
        'path' => 'tcp://redis',
        'host' => 'redis',
        'port' => 6379,
        'database' => 2,
        'prefix' => [
            'session' => ':mbkCrmSession:',
            'modelsMetadata' => ':mbkCrmModelsMetadata:',
            'modelsCache' => ':mbkCrmCacheModel:',
            'settings' => ':mbkCrmSettings:',
            'ajaxUploadService' => ':mbkCrmAjaxUploadHash',
        ]
    ],
    'logger' => [
        'host' => 'logger',
        'port' => 8088
    ],
    'environment' => 'dev',
];