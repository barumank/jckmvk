<?php


namespace Backend\Library\Parsers\SDK\Interfaces;


interface Attribute
{
    public function getAttributeId():?int;
    public function setAttributeId(int $attributeId):Attribute;

    public function getProductId():?int;
    public function setProductId(int $productId):Attribute;

    public function getValue():?string;
    public function setValue(string $value):Attribute;

    public function hash():?string;
}