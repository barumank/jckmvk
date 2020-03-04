<?php


namespace Backend\Library\Parsers\Teplo3000\Adapter\Decorators;

use Backend\Library\Parsers\SDK\Interfaces\Attribute;
use Backend\Library\Parsers\SDK\Interfaces\Category;
use Backend\Library\Parsers\SDK\Interfaces\Product as ProductInterface;
use Backend\Library\Parsers\Teplo3000\Parser\TDO\Product as TeploProduct;

class Product implements ProductInterface
{
    /**@var int|null */
    private $id;
    /**@var int|null */
    private $userId;
    /**@var string|null */
    private $vendorCode;
    /**@var float|null */
    private $rcc;
    /**@var float|null */
    private $discount;
    /**@var int|null */
    private $type;
    /**@var Category[]|null */
    private $categories;
    /**@var Attribute[]|null */
    private $attributes;

    /**@var TeploProduct */
    private $productSource;

    /**
     * Product constructor.
     * @param TeploProduct $productSource
     */
    public function __construct(TeploProduct $productSource)
    {
        $this->productSource = $productSource;
    }

    /**
     * @return TeploProduct
     */
    public function getProductSource(): TeploProduct
    {
        return $this->productSource;
    }

    /**
     * @param TeploProduct $productSource
     * @return Product
     */
    public function setProductSource(TeploProduct $productSource): Product
    {
        $this->productSource = $productSource;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): ProductInterface
    {
        $this->id = $id;
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): ProductInterface
    {
        $this->userId = $userId;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->productSource->getName();
    }

    public function setName(string $name): ProductInterface
    {
        $this->productSource->setName($name);
        return $this;
    }

    public function getVendorCode(): ?string
    {
        return $this->vendorCode;
    }

    public function setVendorCode(string $vendorCode): ProductInterface
    {
        $this->vendorCode = $vendorCode;
        return $this;
    }

    public function getRrc(): ?float
    {
        return $this->rcc;
    }

    public function setRrc(?float $rrc): ProductInterface
    {
        $this->rcc = $rrc;
        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(?float $discount): ProductInterface
    {
        $this->discount = $discount;
        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->productSource->getPrice();
    }

    public function setAmount(float $amount): ProductInterface
    {
        $this->productSource->setPrice($amount);
        return $this;
    }

    public function getImage(): ?string
    {
        return  $this->productSource->getImage();
    }

    public function setImage(string $image): ProductInterface
    {
        $this->productSource->setImage($image);
        return $this;
    }

    public function getImageName(): ?string
    {
        return  $this->productSource->getImageName();
    }


    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): ProductInterface
    {
        $this->type = $type;
        return $this;
    }

    public function hash(): ?string
    {
        return hash('sha256', "{$this->vendorCode}@{$this->userId}");
    }

    public function getCategories(): ?array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): ProductInterface
    {
        $this->categories = $categories;
        return $this;
    }

    public function getAttributes(): ?array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): ProductInterface
    {
        $this->attributes = $attributes;
        return $this;
    }

}