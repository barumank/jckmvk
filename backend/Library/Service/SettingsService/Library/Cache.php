<?php
namespace Backend\Library\Service\SettingsService\Library;

class Cache extends \Backend\Library\Cache\Cache
{
    const KEY_SETTINGS = 'settings';

    public function clear()
    {
        return $this->delete(self::KEY_SETTINGS);
    }

}