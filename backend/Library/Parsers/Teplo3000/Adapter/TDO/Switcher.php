<?php


namespace Backend\Library\Parsers\Teplo3000\Adapter\TDO;


use Backend\Library\Parsers\Teplo3000\Parser\TDO\Attribute;

class Switcher implements AttributeValueInterface
{
    /** @var SwitchValue[] */
    private $switchValues = [];
    /**@var Attribute */
    private $sourceAttribute;
    /** @var AttributeValue */
    private $attributeValue;


    /**
     * ValueSwitcher constructor.
     * @param SwitchValue[] $switchValues
     */
    public function __construct(array $switchValues)
    {
        $this->switchValues = $switchValues;
    }

    /**
     * @return int
     */
    public function getAttributeId(): int
    {
        if (!empty($this->attributeValue)) {
            return $this->attributeValue->getAttributeId();
        }
        return 0;
    }

    /**
     * @param Attribute $sourceAttribute
     * @return AttributeValue
     */
    public function setSourceAttribute(Attribute $sourceAttribute): AttributeValueInterface
    {
        $this->sourceAttribute = $sourceAttribute;
        $this->attributeValue = null;
        foreach ($this->switchValues as $switchValue) {
            $switchValue->setSourceAttribute($sourceAttribute);
            if ($switchValue->isValid()) {
                $this->attributeValue = $switchValue->getAttributeValue();
                $this->attributeValue->setSourceAttribute($sourceAttribute);
                break;
            }
        }
        return $this;
    }

    public function isValid(): bool
    {
        if(empty($this->attributeValue)){
            return false;
        }
        return $this->attributeValue->isValid();
    }

    public function getValue(): string
    {
        if(empty($this->attributeValue)){
            return '';
        }
        return $this->attributeValue->getValue();
    }

}