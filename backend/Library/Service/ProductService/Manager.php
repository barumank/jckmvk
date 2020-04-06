<?php


namespace Backend\Library\Service\ProductService;


use Backend\Library\Service\PaginationService\JsonManager;
use Backend\Models\MySQL\DAO\AttributeGroup;
use Backend\Models\MySQL\DAO\AttributeProduct;
use Backend\Models\MySQL\DAO\Product;
use Backend\Models\MySQL\DAO\ProductCategory;
use Backend\Models\MySQL\DAO\ProductToAttribute;
use Backend\Models\MySQL\DAO\ProductToCategory;
use Phalcon\Mvc\User\Component;

/**
 * Class Manager
 * @package Backend\Library\Service\ProductService
 * @property \Backend\Library\Service\CategoryService\Manager categoryService
 */
class Manager extends Component
{
    /**
     * @var ProductCategory|null
     */
    private $productCategory;
    /**
     * @var int
     */
    private $page;
    /**
     * @var int
     */
    private $type;
    /**
     * @var int
     */
    private $userId;
    /**
     * @var string|null
     */
    private $search;
    /**
     * @var string|null
     */
    private $productColumns;
    /**
     * @var string|null
     */
    private $productAttributeColumns;
    /**
     * @var JsonManager
     */
    private $paginateService;
    /**
     * @var bool
     */
    private $isInitService = false;
    /**
     * @var int[]|null
     */
    private $productIdList;

    /**
     * Manager constructor.
     */
    public function __construct()
    {
        $this->paginateService = new JsonManager();
    }


    /**
     * @return ProductCategory|null
     */
    public function getProductCategory(): ?ProductCategory
    {
        return $this->productCategory;
    }

    /**
     * @param ProductCategory|null $productCategory
     * @return Manager
     */
    public function setProductCategory(?ProductCategory $productCategory): Manager
    {
        $this->productCategory = $productCategory;
        $this->clear();
        return $this;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     * @return Manager
     */
    public function setPage(int $page): Manager
    {
        $this->page = $page;
        $this->clear();
        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return Manager
     */
    public function setType(int $type): Manager
    {
        $this->type = $type;
        $this->clear();
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return Manager
     */
    public function setUserId(int $userId): Manager
    {
        $this->userId = $userId;
        $this->clear();
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSearch(): ?string
    {
        return $this->search;
    }

    /**
     * @param string|null $search
     * @return Manager
     */
    public function setSearch(?string $search): Manager
    {
        $this->search = $search;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getProductColumns(): ?string
    {
        return $this->productColumns;
    }

    /**
     * @param string|null $productColumns
     * @return Manager
     */
    public function setProductColumns(?string $productColumns): Manager
    {
        if (!empty($productColumns)) {
            $productColumns .= ',id';
        }
        $this->productColumns = $productColumns;
        $this->clear();
        return $this;
    }

    /**
     * @return string|null
     */
    public function getProductAttributeColumns(): ?string
    {
        return $this->productAttributeColumns;
    }

    /**
     * @param string|null $productAttributeColumns
     * @return Manager
     */
    public function setProductAttributeColumns(?string $productAttributeColumns): Manager
    {
        if (!empty($productAttributeColumns)) {
            $productAttributeColumns .= ',id,attribute_id,value';
        }
        $this->productAttributeColumns = $productAttributeColumns;
        $this->clear();
        return $this;
    }

    public function getProducts()
    {
        $this->init();
        return $this->paginateService->getPaginate()->items;
    }

    public function getPagination()
    {
        $this->init();
        return $this->paginateService->getPagination();
    }

    public function getProductAttributes()
    {
        $out = [];
        $productIdList = $this->getProductIdList();
        if (empty($productIdList)) {
            return $out;
        }
        $groupProperty = AttributeProduct::findGroupPropertyByProductIdList($productIdList);
        $productPropertyBuilder = ProductToAttribute::findByProductIdList($productIdList);
        ProductToAttribute::bindFields($productPropertyBuilder, $this->productAttributeColumns);
        $productAttributes = $productPropertyBuilder->getQuery()->execute();
        foreach ($productAttributes as $item) {
            $item = (array)$item;
            if (isset($groupProperty[$item['attribute_id']])) {
                $item['value'] = $groupProperty[$item['attribute_id']]->getName();
            }
            $out[] = $item;
        }
        return $out;
    }

    public function getAttributeNames()
    {
        $attributes = [];
        if (!empty($this->productCategory)) {
            $childrenCategoryIdList = $this->categoryService->setProductCategory($this->productCategory)->getChildrenIdList();
            $childrenCategoryIdList[] = $this->productCategory->getId();
            $attributes = AttributeProduct::findByCategoryIdList($childrenCategoryIdList);
        } else {
            $attributes = AttributeProduct::findByProductTypeAndUserId($this->type, $this->userId);
        }
        $attributeNames = [];
        $attributeGroupIdMap = [];
        foreach ($attributes as $attribute) {
            $attributeId = $attribute->getId();
            $groupId = $attribute->getGroupId();
            if (empty($groupId)) {
                $attributeNames[] = [
                    'id' => md5($attribute->getId()),
                    'name' => $attribute->getName(),
                    'order' => $attribute->getOrder(),
                    'unit' => $attribute->getUnit(),
                    'idList' => [$attribute->getId()]
                ];
            } else {
                $attributeGroupIdMap[$groupId][$attributeId] = $attributeId;
            }
        }
        $attributeGroups = AttributeGroup::findByIdList(array_keys($attributeGroupIdMap));
        foreach ($attributeGroups as $attributeGroup) {
            $groupId = $attributeGroup->getId();
            if (isset($attributeGroupIdMap[$groupId])) {
                $attributeNames[] = [
                    'id' => md5("group_{$groupId}"),
                    'name' => $attributeGroup->getName(),
                    'order' => '0',
                    'unit' => $attributeGroup->getUnit(),
                    'idList' => array_values($attributeGroupIdMap[$groupId])
                ];
            }
        }
        return $attributeNames;
    }


    private function getProductIdList()
    {
        if (!empty($this->productIdList)) {
            return $this->productIdList;
        }
        $this->productIdList = [];
        $products = $this->getProducts();
        foreach ($products as $item) {
            $this->productIdList[] = $item['id'];
        }
        return $this->productIdList;
    }


    private function init(): void
    {
        if ($this->isInitService) {
            return;
        }
        $builder = Product::getBuilder();
        Product::bindFields($builder, $this->productColumns);
        $builder->andWhere('(p.user_id = :userId: or p.type =:type:) and p.type = :requestType:', [
            'type' => ProductCategory::TYPE_ALLOW_ALL,
            'requestType' => $this->type,
            'userId' => $this->userId,
        ]);
        if (!empty($this->productCategory)) {
            $childrenCategoryIdList = $this->categoryService->setProductCategory($this->productCategory)->getChildrenIdList();
            $childrenCategoryIdList[] = $this->productCategory->getId();
            ProductToCategory::leftJoinProduct($builder);
            $builder->andWhere('pToC.category_id in ({categoryIdList:array})', [
                'categoryIdList' => $childrenCategoryIdList
            ]);
        }
        if(!empty($this->search)){
            $builder->andWhere('LOWER(p.name) like :search:', [
                'search' => '%' . html_entity_decode(mb_strtolower($this->search)) . '%'
            ]);
        }
        $this->paginateService->execute($builder, $this->page, 30);
        $this->isInitService = true;
    }

    private function clear()
    {
        $this->isInitService = false;
        $this->productIdList = null;
    }

    /**
     * @param array|null $productIdList
     * @return $this
     */
    public function setProductIdList(?array $productIdList)
    {
        $this->productIdList = $productIdList;

        return $this;
    }

}