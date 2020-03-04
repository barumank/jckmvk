<?php


namespace Backend\Library\Parsers\Teplo3000\Parser\TDO;


class Attribute
{

    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $value;

    /**
     * ProductProperty constructor.
     * @param string $key
     * @param string $value
     */
    public function __construct(string $key, string $value)
    {
        $this->key = $key;
        $this->value = $value;
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
     * @return Attribute
     */
    public function setKey(string $key): Attribute
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Attribute
     */
    public function setValue(string $value): Attribute
    {
        $this->value = $value;
        return $this;
    }
}