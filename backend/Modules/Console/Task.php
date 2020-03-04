<?php

namespace Backend\Modules\Console;

/**
 * Class Task
 *
 * @author Artem Pasvlovskiy tema23p@gmail.com
 * @property \Redis $redis
 * @property \Phalcon\Cli\Console $console
 * @property \Phalcon\Config $config
 * @property \Backend\Library\Service\SettingsService\Manager $settingsService
 * @property \Backend\Library\Service\StatisticService\Manager $statisticService
 * @property \Phalcon\Logger\Adapter logger
 * @property \Backend\Library\Service\EmailService\Manager emailService
 * @property \Backend\Library\Phalcon\Db\MysqlAdapter db
 * @property \Backend\Library\Service\FileService\Manager fileService
 * @property \Backend\Library\Service\CategoryService\Manager categoryService
 */
class Task extends \Phalcon\Cli\Task
{

}