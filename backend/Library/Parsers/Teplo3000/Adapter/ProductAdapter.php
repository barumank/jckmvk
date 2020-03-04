<?php

namespace Backend\Library\Parsers\Teplo3000\Adapter;

use Backend\Library\Parsers\SDK\Interfaces\Category as CategoryInterface;
use Backend\Library\Parsers\SDK\Interfaces\Product as ProductInterface;
use Backend\Library\Parsers\Teplo3000\Adapter\Decorators\Attribute;
use Backend\Library\Parsers\Teplo3000\Adapter\Decorators\Category;
use Backend\Library\Parsers\Teplo3000\Adapter\Decorators\Product;
use Backend\Library\Parsers\Teplo3000\Adapter\TDO\AttributeValue;
use Backend\Library\Parsers\Teplo3000\Adapter\TDO\AttributeValueInterface;
use Backend\Library\Parsers\Teplo3000\Parser\TDO\Attribute as AttributeParser;
use Backend\Library\Parsers\Teplo3000\Parser\TDO\Category as CategoryParser;
use Backend\Library\Parsers\Teplo3000\Parser\TDO\Product as ParserProduct;
use Backend\Library\Phalcon\Logger\TcpLogger;
use Phalcon\Logger\Adapter as LoggerAdapter;

class ProductAdapter
{
    /**@var ParserProduct[] */
    private $sourceProducts = [];
    /**@var int */
    private $userId;
    /**@var int */
    private $type;
    private $categoryHashMap = [];
    /**@var TcpLogger */
    private $logger;
    /** @var AttributeValue[] */
    private $dataMapper;

    public function __construct(array $sourceProducts, int $userId, int $type = 0)
    {
        $this->sourceProducts = $sourceProducts;
        $this->userId = $userId;
        $this->type = $type;
        $this->dataMapper = require __DIR__ . '/dataMapper.php';
    }

    /**
     * @param LoggerAdapter $logger
     * @return ProductAdapter
     */
    public function setLogger(LoggerAdapter $logger): ProductAdapter
    {
        $this->logger = $logger;
        return $this;
    }


    /**
     * @return ProductInterface[]
     */
    public function getProducts()
    {
        $products = [];
        foreach ($this->sourceProducts as $sourceProduct) {
            /**@var Product $product */
            $product = (new Product($sourceProduct))
                ->setUserId($this->userId)
                ->setType($this->type)
                ->setCategories($this->getCategories($sourceProduct->getCategories()));
            $this->setAttributes($product, $sourceProduct->getProperty());
            $products[] = $product;
        }
        return $products;
    }

    /**
     * @param CategoryParser[] $sourceCategories
     * @return CategoryInterface[]
     */
    private function getCategories(array $sourceCategories): array
    {
        $out = [];
        foreach ($sourceCategories as $sourceCategory) {
            /**@var Category $category */
            $category = (new Category($sourceCategory))
                ->setType($this->type)
                ->setUserId($this->userId);
            $hash = $category->hash();
            if (isset($this->categoryHashMap[$hash])) {
                $category = $this->categoryHashMap[$hash];
            }
            $out[] = $category;
        }
        return $out;
    }

    /**
     * @param Product $product
     * @param AttributeParser[] $sourceAttributes
     */
    public function setAttributes(Product $product, array $sourceAttributes): void
    {
//        ->setAttributes()
//        ->setVendorCode()
//        ->setRrc()
//        ->setDiscount();
        $attributes = [];
        foreach ($sourceAttributes as $sourceAttribute) {
            $attributeKey = $sourceAttribute->getKey();
            switch ($attributeKey) {
                case 'Артикул производителя':
                    $product->setVendorCode($sourceAttribute->getValue())
                        ->setDiscount(null)
                        ->setRrc(null);
                    break;
                case 'Габариты (ДхШхВ)':
                    $value = $sourceAttribute->getValue();
                    $values = explode('x', $value);
                    $values = str_replace([' ', 'мм'], ['', ''], $values);
                    list($length, $width, $height) = $values;
                    $attributes[] = (new Attribute($sourceAttribute))
                        ->setAttributeId(263)
                        ->setValue($length);
                    $attributes[] = (new Attribute($sourceAttribute))
                        ->setAttributeId(264)
                        ->setValue($width);
                    $attributes[] = (new Attribute($sourceAttribute))
                        ->setAttributeId(265)
                        ->setValue($height);
                    break;
                case 'Габариты (ШxВxГ)':
                    $value = $sourceAttribute->getValue();
                    $values = explode('x', $value);
                    $values = str_replace([' ', 'мм'], ['', ''], $values);
                    list($width, $height, $depth) = $values;
                    $attributes[] = (new Attribute($sourceAttribute))
                        ->setAttributeId(264)
                        ->setValue($width);
                    $attributes[] = (new Attribute($sourceAttribute))
                        ->setAttributeId(265)
                        ->setValue($height);
                    $attributes[] = (new Attribute($sourceAttribute))
                        ->setAttributeId(413)
                        ->setValue($depth);
                    break;
                case 'Габариты (ГхШхВ)':
                    $value = $sourceAttribute->getValue();
                    $values = explode('x', $value);
                    $values = str_replace([' ', 'мм'], ['', ''], $values);
                    list($depth, $width, $height,) = $values;
                    $attributes[] = (new Attribute($sourceAttribute))
                        ->setAttributeId(264)
                        ->setValue($width);
                    $attributes[] = (new Attribute($sourceAttribute))
                        ->setAttributeId(265)
                        ->setValue($height);
                    $attributes[] = (new Attribute($sourceAttribute))
                        ->setAttributeId(413)
                        ->setValue($depth);
                    break;
                case 'Габариты (ВхШхГ)':
                    $value = $sourceAttribute->getValue();
                    $values = explode('x', $value);
                    $values = str_replace([' ', 'мм'], ['', ''], $values);
                    list($height,$width,$depth) = $values;
                    $attributes[] = (new Attribute($sourceAttribute))
                        ->setAttributeId(264)
                        ->setValue($width);
                    $attributes[] = (new Attribute($sourceAttribute))
                        ->setAttributeId(265)
                        ->setValue($height);
                    $attributes[] = (new Attribute($sourceAttribute))
                        ->setAttributeId(413)
                        ->setValue($depth);
                    break;
                default:
                    if (isset($this->dataMapper[$attributeKey])) {
                        /**@var AttributeValueInterface $attributeValue */
                        $attributeValue = $this->dataMapper[$attributeKey];
                        $attributeValue->setSourceAttribute($sourceAttribute);
                        if ($attributeValue->isValid()) {
                            $attribute = (new Attribute($sourceAttribute))
                                ->setAttributeId($attributeValue->getAttributeId())
                                ->setValue($attributeValue->getValue());
                            $attributes[] = $attribute;
                        }
                    }
            }
        }
        $product->setAttributes($attributes);
    }
}