<?php


namespace Backend\Library\Service\FileService;

use Backend\Library\Service\FileService\Library\OrganizationService;
use Backend\Library\Service\FileService\Library\ProductCategoryService;
use Backend\Library\Service\FileService\Library\UserService;
use Phalcon\Mvc\User\Component;

/**
 * Class Manager
 * @package Backend\Library\Service\FileService
 * @property \Phalcon\Config config
 */
class Manager extends Component
{
    /**
     * @var int|null
     */
    private $userId;
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var ProductCategoryService
     */
    private $productCategoryService;

    /**
     * @var OrganizationService
     */
    private $organizationService;

    public function __construct()
    {
        $this->userService = (new UserService())
            ->setConfig($this->config);
        $this->productCategoryService = (new ProductCategoryService())
            ->setConfig($this->config);
        $this->organizationService = (new OrganizationService())
            ->setConfig($this->config);
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
        $this->userService->setUserId($userId);
        $this->productCategoryService->setUserId($userId);
        $this->organizationService->setUserId($userId);
        return $this;
    }

    /**
     * @return UserService
     */
    public function getUserService(): UserService
    {
        return $this->userService;
    }

    /**
     * @return ProductCategoryService
     */
    public function getProductCategoryService(): ProductCategoryService
    {
        return $this->productCategoryService;
    }

    /**
     * @return OrganizationService
     */
    public function getOrganizationService(): OrganizationService
    {
        return $this->organizationService;
    }


    public function saveProductImageSource(string $imageName, string $imageSource): bool
    {
        $usersDir = $this->config->path('dirs.users');
        $dir = "{$usersDir}/{$this->userId}/product_images";
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
            chmod($dir, 0777);
        }
        $fileName = "{$dir}/{$imageName}";
        if (file_put_contents($fileName, $imageSource) === false) {
            return false;
        }
        $smileFileName = "{$dir}/small_{$imageName}";
        if (!copy($fileName, $smileFileName)) {
            unlink($fileName);
            return false;
        }
        $image = (new \Phalcon\Image\Adapter\Imagick($smileFileName))
            ->resize(
                350,
                350,
                \Phalcon\Image::AUTO
            )->save();
        chmod($fileName, 0777);
        chmod($smileFileName, 0777);
        return true;
    }

    public function removeProductImage(string $imageName): bool
    {
        $usersDir = $this->config->path('dirs.users');
        $dir = "{$usersDir}/{$this->userId}/product_images";
        $fileName = "{$dir}/{$imageName}";
        $smileFileName = "{$dir}/small_{$imageName}";
        if (file_exists($fileName) && !unlink($fileName)) {
            return false;
        }
        if (file_exists($smileFileName) && !unlink($smileFileName)) {
            return false;
        }
        return true;
    }

}