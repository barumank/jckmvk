<?php

namespace Backend\Library\Service\AjaxUploadService;

use Backend\Library\Service\AjaxUploadService\Library\FileInRedis;
use Backend\Library\Service\AjaxUploadService\Library\FileResponse;
use Phalcon\Mvc\User\Component;

/**
 * Class Manager
 * @package Backend\Library\Service\AjaxUploadService
 * @property \Redis redis
 */
class Manager extends Component
{

    private $hashKey;

    /**
     * @var integer
     */
    private $expire;

    /**
     * Manager constructor.
     * @param $hashKey
     * @param int $expire
     */
    public function __construct($hashKey, int $expire = 1800)
    {
        $this->hashKey = $hashKey;
        $this->expire = $expire;

        if (!file_exists($this->config->ajaxUploadService->cacheDir)) {
            mkdir($this->config->ajaxUploadService->cacheDir, 0777, true);
            chmod($this->config->ajaxUploadService->cacheDir,0777);
        }

        $this->updateRedisKeys();
    }

    /**
     * @param \Phalcon\Http\Request\File $httpFile
     * @return FileResponse|null
     */
    public function upload($httpFile)
    {
        $key = md5(uniqid("", true) . time());
        $redisFile = new FileInRedis($key, "{$key}.{$httpFile->getExtension()}", time() + $this->expire);

        $movePath = "{$this->config->ajaxUploadService->cacheDir}/{$redisFile->getName()}";

        if (!$httpFile->moveTo($movePath)) {
            return null;
        }
        if (!$this->save($redisFile)) {
            return null;
        }
        $webPath = "{$this->config->ajaxUploadService->webDir}/{$redisFile->getName()}";
        return new FileResponse($key, $webPath, $redisFile->getName());
    }

    public function deleteByKey($key)
    {
        $redisFile = $this->getFile($key);
        return $this->delete($redisFile);
    }

    /**
     * @param $key
     * @return FileResponse|null
     */
    public function getFileByKey($key)
    {
        if (empty($key)) {
            return null;
        }

        if ($this->redis->hExists($this->hashKey, $key)) {
            /**@var FileInRedis $redisFile */
            $redisFile = unserialize($this->redis->hGet($this->hashKey, $key));
            $webPath = "{$this->config->ajaxUploadService->webDir}/{$redisFile->getName()}";
            return new FileResponse($key, $webPath, $redisFile->getName());
        }
        return null;
    }

    public function moveTo($key, $toPath)
    {
        $redisFile = $this->getFile($key);
        if (empty($redisFile)) {
            return false;
        }
        $dir = dirname($toPath);
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
            chmod($dir,0777);
        }
        $movePath = "{$this->config->ajaxUploadService->cacheDir}/{$redisFile->getName()}";
        if (!copy($movePath, $toPath)) {
            return false;
        }
        $this->delete($redisFile);
        chmod($toPath,0777);
        return true;
    }

    private function getFile($key)
    {
        $file = null;
        if ($this->redis->hExists($this->hashKey, $key)) {
            $file = unserialize($this->redis->hGet($this->hashKey, $key));
        }
        return $file;
    }

    public function updateRedisKeys($keys = [])
    {
        $time = time();
        $keys = array_flip($keys);
        $redisValueList = $this->redis->hGetAll($this->hashKey);
        foreach ($redisValueList as $hash => $item) {
            /**@var FileInRedis $redisFile */
            $redisFile = unserialize($item);

            if (!empty($keys) && isset($keys[$hash])) {
                $redisFile->setExpireTime($time + $this->expire);
                $this->save($redisFile);
            }

            if ($redisFile->getExpireTime() < $time) {
                $this->delete($redisFile);
            }
        }
    }

    private function delete(FileInRedis $redisFile)
    {
        $path = "{$this->config->ajaxUploadService->cacheDir}/{$redisFile->getName()}";
        if (is_file($path) && unlink($path)) {
            $this->redis->hDel($this->hashKey, $redisFile->getKey());
        }
        return false;
    }

    private function save(FileInRedis $redisFile)
    {
        return $this->redis->hSet($this->hashKey, $redisFile->getKey(), serialize($redisFile));
    }
}