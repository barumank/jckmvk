<?php


namespace Backend\Library\Service\CategoryService;

use Backend\Library\Service\CategoryService\Library\BaseCategoryBaseHelper;
use Backend\Library\Service\CategoryService\Library\CustomCategoryBaseHelper;
use Backend\Models\MySQL\DAO\ProductCategory;
use Phalcon\Mvc\User\Component;

/**
 * Class Manager
 * @package Backend\Library\Service\CategoryService
 * @property \Phalcon\Config $config
 */
class Manager extends Component
{
    /**
     * @var int|null
     */
    private $userId;
    /**
     * @var int|null
     */
    private $categoryId;
    /**
     * @var int|null
     */
    private $categoryType;
    /**
     * @var BaseCategoryBaseHelper
     */
    private $baseHelper;
    /**
     * @var CustomCategoryBaseHelper
     */
    private $customHelper;


    /**
     * Manager constructor.
     */
    public function __construct()
    {
        $cashDir = $this->config->path('dirs.cacheCategory');
        if (!file_exists($cashDir)) {
            mkdir($cashDir, 0777, true);
        }
        $this->baseHelper = new BaseCategoryBaseHelper($cashDir,$this->db);
        $this->customHelper = new CustomCategoryBaseHelper($cashDir,$this->db);
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int|null $userId
     * @return Manager
     */
    public function setUserId(?int $userId): Manager
    {
        $this->userId = $userId;
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
     * @return Manager
     */
    public function setCategoryId(?int $categoryId): Manager
    {
        $this->categoryId = $categoryId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCategoryType(): ?int
    {
        return $this->categoryType;
    }

    /**
     * @param int|null $categoryType
     * @return Manager
     */
    public function setCategoryType(?int $categoryType): Manager
    {
        $this->categoryType = $categoryType;
        return $this;
    }

    /**
     * @param ProductCategory|null $productCategory
     * @return Manager
     */
    public function setProductCategory(?ProductCategory $productCategory): Manager
    {
        if (!empty($productCategory)) {
            $this->userId = $productCategory->getUserId();
            $this->categoryId = $productCategory->getId();
            $this->categoryType = (int)$productCategory->getType();
        }
        return $this;
    }


    public function getParentIdList()
    {
        $this->initHelpers();
        if (ProductCategory::TYPE_ALLOW_USER === (int)$this->categoryType) {
            return $this->customHelper->getParentIdList();
        }
        return $this->baseHelper->getParentIdList();
    }

    public function getChildrenIdList()
    {
        $this->initHelpers();
        if (ProductCategory::TYPE_ALLOW_USER === (int)$this->categoryType) {
            return $this->customHelper->getChildrenIdList();
        }
        return $this->baseHelper->getChildrenIdList();

    }

    public function hasChildren(): bool
    {
        $this->initHelpers();
        if (ProductCategory::TYPE_ALLOW_USER === (int)$this->categoryType) {
            return $this->customHelper->hasChildren($this->categoryId);
        }
        return $this->baseHelper->hasChildren($this->categoryId);
    }

    public function cacheClearBase():bool
    {
        return $this->baseHelper->clearCache();
    }

    public function cacheClearCustom():bool
    {
        $this->initHelpers();
        return $this->customHelper->clearCache();
    }

    private function initHelpers(): void
    {
        $this->baseHelper
            ->setCategoryId($this->categoryId);
        $this->customHelper
            ->setUserId($this->userId)
            ->setCategoryId($this->categoryId);

    }


}
