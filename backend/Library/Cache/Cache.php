<?php
namespace Backend\Library\Cache;

use Phalcon\Di\FactoryDefault;

abstract class Cache
{

    /**
     * @var \Phalcon\Cache\Multiple
     */
    private $cache;
    /**
     * @var string
     */
    private $cachePrefix = '';

    private $liveCache = [];

    public function __construct($cachePrefix)
    {
        $this->cache       = FactoryDefault::getDefault()->get('modelsCache');
        $this->cachePrefix = $cachePrefix;
    }


    /**
     * Checks if cache exists in at least one backend
     *
     * @param string|int $keyName
     * @param int        $lifetime
     *
     * @return bool
     */
    public function exists($keyName = null, $lifetime = null)
    {
        if ( isset($this->liveCache[$keyName]) ) {
            return true;
        }

        return $this->cache->exists($this->cachePrefix . $keyName, $lifetime);
    }

    /**
     * Returns a cached content reading the internal backends
     *
     * @param string|int $keyName
     * @param int        $lifetime
     *
     * @return mixed
     */
    public function get($keyName, $lifetime = null)
    {
        if ( isset($this->liveCache[$keyName]) ) {
            return $this->liveCache[$keyName];
        }

        $this->liveCache[$keyName] = $this->cache->get($this->cachePrefix . $keyName, $lifetime);

        return $this->liveCache[$keyName];
    }

    /**
     * Stores cached content into all backends and stops the frontend
     *
     * @param string  $keyName
     * @param string  $content
     * @param int     $lifetime
     * @param boolean $stopBuffer
     */
    public function save($keyName = null, $content = null, $lifetime = null, $stopBuffer = null)
    {

        $this->liveCache[$keyName] = $content;

        if ( $keyName !== null ) {
            $keyName = $this->cachePrefix . $keyName;
        }

        if ( $lifetime === null ) {
            $lifetime = -1;
        }

        return $this->cache->save($keyName, $content, $lifetime, $stopBuffer);
    }

    /**
     * Deletes a value from each backend
     *
     * @param string|int $keyName
     *
     * @return bool
     */
    public function delete($keyName)
    {
        return $this->cache->delete($this->cachePrefix . $keyName);
    }

    abstract public function clear();

}