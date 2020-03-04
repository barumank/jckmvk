<?php

namespace Backend\Library\Service\SettingsService;

use Backend\Library\Service\SettingsService\Library\Cache;
use Backend\Library\Service\SettingsService\TDO\PercentUser;
use Backend\Models\MySQL\DAO\Settings;

/**
 * Class SettingsService
 *
 * @author  Artem Pasvlovskiy tema23p@gmail.com
 *
 * @package Backend\Library\Service
 */
class Manager
{
    
    const KEY_PERCENT_USER = 'percent_user_amount';
    const KEY_PERCENT_PARTNER_PROGRAM = 'percent_partner_program_amount';

    /**
     * @var Settings[]
     */
    private $settingsMap;

    private $cache;

    public function __construct($cachePrefixName)
    {
        $this->cache = new Cache($cachePrefixName);
        $this->init();
    }

    protected function init()
    {
        $this->settingsMap = [];
        if (!$this->cache->exists(Cache::KEY_SETTINGS)) {
            $query = Settings::find();
            foreach ($query as $item) {
                $this->settingsMap[$item->getKey()] = $item;
            }
            $this->cache->save(Cache::KEY_SETTINGS, $this->settingsMap);
        } else {
            $this->settingsMap = $this->cache->get(Cache::KEY_SETTINGS);
        }
    }

    public function updateService()
    {
        $this->settingsMap = [];
        $query = Settings::find();
        foreach ($query as $item) {
            $this->settingsMap[$item->getKey()] = $item;
        }
        $this->cache->save(Cache::KEY_SETTINGS, $this->settingsMap);
    }
    /**
     * @return PercentUser
     */
    public function getPercentUserObject()
    {
        $model = $this->settingsMap[self::KEY_PERCENT_USER] ?? new Settings();
        return new PercentUser($model->getValue());
    }
    /**
     * @param PercentUser $percentUserAmount
     * @return bool
     */
    public function setPercentUserObject($percentUserAmount)
    {
        $model = $this->settingsMap[self::KEY_PERCENT_USER] ?? new Settings();
        return $model
            ->setValue($percentUserAmount->toArray())
            ->setKey(self::KEY_PERCENT_USER)
            ->save();
    }
    public function getPercentUserValue()
    {
        return $this->getPercentUserObject()->getPercent();
    }

    /**
     * @return PercentUser
     */
    public function getPercentPartnerProgramObject()
    {
        $model = $this->settingsMap[self::KEY_PERCENT_PARTNER_PROGRAM] ?? new Settings();
        return new PercentUser($model->getValue());
    }
    /**
     * @param PercentUser $percentUser
     * @return bool
     */
    public function setPercentPartnerProgramObject($percentUser)
    {
        $model = $this->settingsMap[self::KEY_PERCENT_PARTNER_PROGRAM] ?? new Settings();
        return $model
            ->setValue($percentUser->toArray())
            ->setKey(self::KEY_PERCENT_PARTNER_PROGRAM)
            ->save();
    }

    public function getPercentPartnerProgramValue()
    {
        return $this->getPercentPartnerProgramObject()->getPercent();
    }

}