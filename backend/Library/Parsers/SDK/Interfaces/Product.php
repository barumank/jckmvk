<?php

namespace Backend\Library\Parsers\SDK\Interfaces;

interface Product
{
    public function getId(): ?int;

    public function setId(int $id): Product;

    public function getUserId(): ?int;

    public function setUserId(int $userId): Product;

    public function getName(): ?string;

    public function setName(string $name): Product;

    public function getVendorCode(): ?string;

    public function setVendorCode(string $vendorCode): Product;

    public function getRrc(): ?float;

    public function setRrc(?float $rrc): Product;

    public function getDiscount(): ?float;

    public function setDiscount(?float $discount): Product;

    public function getAmount(): ?float;

    public function setAmount(float $amount): Product;

    public function getImage(): ?string;

    public function setImage(string $image): Product;

    public function getType(): ?int;

    public function setType(int $type): Product;

    public function hash(): ?string;

    /**@return Category[] */
    public function getCategories(): ?array;

    /**
     * @param Category[] $categories
     * @return Product
     */
    public function setCategories(array $categories): Product;

    /**@return Attribute[] */
    public function getAttributes(): ?array;

    /**
     * @param Attribute[] $attributes
     * @return Product
     */
    public function setAttributes(array $attributes): Product;

    public function getImageName():?string;
}