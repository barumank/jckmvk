<?php
namespace Backend\Library\Parsers\Teplo3000\Parser;
use Backend\Library\Parsers\Teplo3000\Parser\TDO\Category;
use Backend\Library\Parsers\Teplo3000\Parser\TDO\Product;

class CategoryComposite
{
    private $categoryHashMap = [];
    /**@var Category[] */
    private $mapChildHashByRootCategory = [];
    /**
     * CategoryComposite constructor.
     * @param Category[] $mapChildHashByRootCategory
     */
    public function __construct(array $mapChildHashByRootCategory)
    {
        $this->mapChildHashByRootCategory = $mapChildHashByRootCategory;
    }

    public function addProduct(Product $product)
    {
        $categories = $product->getCategories();
        //добавляем рутовые категории к дочерним
        if (!empty($categories)) {
            $firstCategory = current($categories);
            $hash = $firstCategory->getHash();
            if (isset($this->mapChildHashByRootCategory[$hash])) {
                array_unshift($categories, $this->mapChildHashByRootCategory[$hash]);
            }
        }
        foreach ($categories as $category) {
            $categoryHash = $category->getHash();
            $this->categoryHashMap[$categoryHash] = $category;
        }
    }

    /**
     * @return Category[]
     */
    public function getCategoryHashMap(): array
    {
        return $this->categoryHashMap;
    }

    /**
     * @param Product[] $products
     * @return Product[]
     */
    public function filterCategory(array $products):array
    {
        foreach ($products as $product) {
            $newCategories = [];
            $categories = $product->getCategories();
            foreach ($categories as $category){
                $hash = $category->getHash();
                if(isset($this->categoryHashMap[$hash])){
                    $newCategories[] = $this->categoryHashMap[$hash];
                }
            }
            $product->setCategories($newCategories);
        }
        return $products;
    }

}
