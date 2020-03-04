<?php

namespace Backend\Library\Parsers\Teplo3000\Adapter\Decorators;

use Backend\Library\Parsers\SDK\Interfaces\Attribute as AttributeInterface;
use Backend\Library\Parsers\Teplo3000\Parser\TDO\Attribute as TeploAttribute;

class Attribute implements AttributeInterface
{
    private $attributeId;
    private $productId;
    private $value;
    private $attributeSource;

    /**
     * Attribute constructor.
     * @param $attributeSource
     */
    public function __construct(TeploAttribute $attributeSource)
    {
        $this->attributeSource = $attributeSource;
    }

    /**
     * @return TeploAttribute
     */
    public function getAttributeSource()
    {
        return $this->attributeSource;
    }

    /**
     * @param TeploAttribute $attributeSource
     * @return Attribute
     */
    public function setAttributeSource(TeploAttribute $attributeSource)
    {
        $this->attributeSource = $attributeSource;
        return $this;
    }


    public function getAttributeId(): ?int
    {
        return $this->attributeId;
    }

    public function setAttributeId(int $attributeId): AttributeInterface
    {
        $this->attributeId = $attributeId;
        return $this;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): AttributeInterface
    {
        $this->productId = $productId;
        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): AttributeInterface
    {
        $this->value = $value;
        return $this;
    }

    public function hash(): ?string
    {
        return hash('sha256', "{$this->attributeId}@{$this->productId}");
    }

}