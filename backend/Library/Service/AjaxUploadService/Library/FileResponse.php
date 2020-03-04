<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 20.03.19
 * Time: 16:54
 */

namespace Backend\Library\Service\AjaxUploadService\Library;


class FileResponse implements \JsonSerializable
{

    private $key;
    private $webPath;
    private $name;

    /**
     * FileResponse constructor.
     * @param $key
     * @param $webPath
     */
    public function __construct($key = '', $webPath = '', $name = '')
    {
        $this->key = $key;
        $this->webPath = $webPath;
        $this->name = $name;
    }


    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     * @return FileResponse
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWebPath()
    {
        return $this->webPath;
    }

    /**
     * @param mixed $webPath
     * @return FileResponse
     */
    public function setWebPath($webPath)
    {
        $this->webPath = $webPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return FileResponse
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }


    public function jsonSerialize()
    {
        return [
            'key' => $this->key,
            'name' => $this->name,
            'webPath' => $this->webPath
        ];
    }


}