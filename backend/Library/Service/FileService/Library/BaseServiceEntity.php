<?php


namespace Backend\Library\Service\FileService\Library;


use Phalcon\Config;

abstract class BaseServiceEntity
{
    /**
     * @var int|null
     */
    protected $userId;
    /**
     * @var Config
     */
    protected $config;

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }
    /**
     * @param int|null $userId
     * @return BaseServiceEntity
     */
    public function setUserId(?int $userId): BaseServiceEntity
    {
        $this->userId = $userId;
        return $this;
    }
    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }
    /**
     * @param Config $config
     * @return BaseServiceEntity
     */
    public function setConfig(Config $config): BaseServiceEntity
    {
        $this->config = $config;
        return $this;
    }

    protected function generateHash():string
    {
        return md5(uniqid('', true) . '@' . microtime(true) . '@' . rand(10000, 99999));
    }
}