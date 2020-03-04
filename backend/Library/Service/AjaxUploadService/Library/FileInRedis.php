<?php

namespace Backend\Library\Service\AjaxUploadService\Library;

class FileInRedis
{
    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $name;
    /**
     * @var int
     */
    private $expireTime;


    /**
     * FileInRedis constructor.
     * @param string $name
     * @param int $expireTime
     * @param string $key
     */
    public function __construct(string $key = '', string $name = '', int $expireTime = 0)
    {
        $this->key = $key;
        $this->name = $name;
        $this->expireTime = $expireTime;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return FileInRedis
     */
    public function setKey(string $key): FileInRedis
    {
        $this->key = $key;
        return $this;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return FileInRedis
     */
    public function setName(string $name): FileInRedis
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpireTime(): int
    {
        return $this->expireTime;
    }

    /**
     * @param int $expireTime
     * @return FileInRedis
     */
    public function setExpireTime(int $expireTime): FileInRedis
    {
        $this->expireTime = $expireTime;

        return $this;
    }


}