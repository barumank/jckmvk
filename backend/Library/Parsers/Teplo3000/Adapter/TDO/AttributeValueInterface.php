<?php


namespace Backend\Library\Parsers\Teplo3000\Adapter\TDO;


use Backend\Library\Parsers\Teplo3000\Parser\TDO\Attribute;

interface AttributeValueInterface
{
    /**
     * @return int
     */
    public function getAttributeId(): int;

    public function setSourceAttribute(Attribute $sourceAttribute): AttributeValueInterface;

    public function isValid(): bool;

    public function getValue(): string;
}