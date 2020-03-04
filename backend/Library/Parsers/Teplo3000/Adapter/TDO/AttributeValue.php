<?php

namespace Backend\Library\Parsers\Teplo3000\Adapter\TDO;

use Backend\Library\Parsers\Teplo3000\Parser\TDO\Attribute;

class AttributeValue implements AttributeValueInterface
{
    /**@var int|null */
    private $attributeId;
    /**@var int[]|null */
    private $groupList;
    /**@var Attribute */
    private $sourceAttribute;
    private $filter;

    /**
     * AttributeValue constructor.
     * @param int $attributeId
     * @param int[] $groupList
     * @param callable|null $filter
     */
    public function __construct(?int $attributeId = null, $groupList = null, ?callable $filter = null)
    {
        $this->attributeId = $attributeId;
        $this->groupList = $groupList;
        $this->filter = $filter;
    }

    /**
     * @return int
     */
    public function getAttributeId(): int
    {
        if (!empty($this->attributeId) && empty($this->groupList)) {
            return $this->attributeId;
        }
        $sourceAttributeValue = $this->sourceAttribute->getValue();
        if (!empty($this->groupList) && isset($this->groupList[$sourceAttributeValue])) {
            return $this->groupList[$sourceAttributeValue];
        }
        return 0;
    }

    /**
     * @param int $attributeId
     * @return AttributeValue
     */
    public function setAttributeId(int $attributeId): AttributeValueInterface
    {
        $this->attributeId = $attributeId;
        return $this;
    }

    /**
     * @return Attribute
     */
    public function getSourceAttribute(): AttributeValueInterface
    {
        return $this->sourceAttribute;
    }

    /**
     * @param Attribute $sourceAttribute
     * @return AttributeValue
     */
    public function setSourceAttribute(Attribute $sourceAttribute): AttributeValueInterface
    {
        $this->sourceAttribute = $sourceAttribute;
        return $this;
    }

    /**
     * @return int[]|null
     */
    public function getGroupList(): ?array
    {
        return $this->groupList;
    }

    /**
     * @param int[]|null $groupList
     * @return AttributeValue
     */
    public function setGroupList(?array $groupList): AttributeValueInterface
    {
        $this->groupList = $groupList;
        return $this;
    }

    public function isValid(): bool
    {
        if (!empty($this->attributeId) && empty($this->groupList)) {
            return true;
        } elseif (!empty($this->groupList)
            && isset($this->groupList[$this->sourceAttribute->getValue()])) {
            return true;
        }
        return false;
    }

    public function getValue(): string
    {
        if (!empty($this->attributeId) && empty($this->groupList)) {
            if(!empty($this->filter)){
                $filter = $this->filter;
                return  $filter($this->sourceAttribute->getValue());
            }
            return $this->sourceAttribute->getValue();
        }
        $sourceAttributeValue = $this->sourceAttribute->getValue();
        if (!empty($this->groupList) && isset($this->groupList[$sourceAttributeValue])) {
            return $this->groupList[$sourceAttributeValue];
        }
        return '';
    }
}