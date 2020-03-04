<?php
namespace Backend\Library\Parsers\SDK\TDO;

class ProductCategory
{
    /**@var int|null*/
    private $productId;
    /**@var int|null*/
    private $categoryId;

    /**
     * ProductToCategory constructor.
     * @param int|null $productId
     * @param int|null $categoryId
     */
    public function __construct(?int $productId, ?int $categoryId)
    {
        $this->productId = $productId;
        $this->categoryId = $categoryId;
    }


    /**
     * @return int|null
     */
    public function getProductId(): ?int
    {
        return $this->productId;
    }

    /**
     * @param int|null $productId
     * @return ProductCategory
     */
    public function setProductId(?int $productId): ProductCategory
    {
        $this->productId = $productId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    /**
     * @param int|null $categoryId
     * @return ProductCategory
     */
    public function setCategoryId(?int $categoryId): ProductCategory
    {
        $this->categoryId = $categoryId;
        return $this;
    }

    public function hash(): ?string
    {
        return hash('sha256', "{$this->productId}@{$this->categoryId}");
    }


}