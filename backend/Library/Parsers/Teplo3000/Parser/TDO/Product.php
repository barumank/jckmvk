<?php

namespace Backend\Library\Parsers\Teplo3000\Parser\TDO;


use Backend\Library\Parsers\Teplo3000\Parser\FileProxy;

class Product
{
    /**
     * @var string|null
     */
    private $name;
    /**
     * @var double|null
     */
    private $price;
    /**
     * @var Attribute[]
     */
    private $property = [];
    /**
     * @var Category[]
     */
    private $categories = [];
    /**
     * @var FileProxy
     */
    private $fileProxy;
    /**
     * @var string|null
     */
    private $urlImage;
    /**
     * @var string|null
     */
    private $imageName;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return Product
     */
    public function setName(?string $name): Product
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     * @return Product
     */
    public function setPrice(?float $price): Product
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return Attribute[]
     */
    public function getProperty(): array
    {
        return $this->property;
    }

    /**
     * @param Attribute[] $property
     * @return Product
     */
    public function setProperty(array $property): Product
    {
        $this->property = $property;
        return $this;
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param Category[] $categories
     * @return Product
     */
    public function setCategories(array $categories): Product
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * @return FileProxy
     */
    public function getFileProxy(): FileProxy
    {
        return $this->fileProxy;
    }

    /**
     * @param FileProxy $fileProxy
     * @return Product
     */
    public function setFileProxy(FileProxy $fileProxy): Product
    {
        $this->fileProxy = $fileProxy;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrlImage(): ?string
    {
        return $this->urlImage;
    }

    /**
     * @param string|null $urlImage
     * @return Product
     */
    public function setUrlImage(?string $urlImage): Product
    {
        $this->urlImage = $urlImage;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->fileProxy->get($this->imageName);
    }

    /**
     * @param string|null $image
     * @return Product
     */
    public function setImage(?string $image): Product
    {
        if (empty($image)) {
            return $this;
        }
        if (empty($this->urlImage)) {
            return $this;
        }
        $name = basename($this->urlImage);
        $extension = pathinfo($name)['extension'];

        $this->imageName = md5(uniqid('',true).microtime(true)).".{$extension}";

        $this->fileProxy->set($this->imageName,$image);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageName(): ?string
    {
        return $this->imageName;
    }
}
