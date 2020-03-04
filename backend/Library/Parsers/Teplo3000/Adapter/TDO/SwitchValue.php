<?php


namespace Backend\Library\Parsers\Teplo3000\Adapter\TDO;


use Backend\Library\Parsers\Teplo3000\Parser\TDO\Attribute;

class SwitchValue
{
    /** @var callable */
    private $validator;
    /**@var AttributeValue */
    private $attributeValue;
    /**@var Attribute */
    private $sourceAttribute;
    /**
     * SwitchValue constructor.
     * @param callable $validator
     * @param AttributeValue $attributeValue
     */
    public function __construct(callable $validator, AttributeValue $attributeValue)
    {
        $this->validator = $validator;
        $this->attributeValue = $attributeValue;
    }
    public function setSourceAttribute(Attribute $sourceAttribute):SwitchValue
    {
        $this->sourceAttribute = $sourceAttribute;
        return $this;
    }
    /**
     * @return AttributeValue
     */
    public function getAttributeValue(): AttributeValue
    {
        return $this->attributeValue;
    }
    public function isValid():bool
    {
        $validator = $this->validator;
        return $validator($this->sourceAttribute->getValue());
    }

}