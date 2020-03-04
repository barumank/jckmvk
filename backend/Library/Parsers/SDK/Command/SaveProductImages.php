<?php


namespace Backend\Library\Parsers\SDK\Command;

use Backend\Library\Phalcon\Db\MysqlAdapter;
use Backend\Library\Phalcon\Logger\TcpLogger;
use Backend\Library\Parsers\SDK\Interfaces\Product as ProductInterface;
use Phalcon\Logger\Adapter as LoggerAdapter;
use \Backend\Library\Service\FileService\Manager as FileService;

class SaveProductImages extends BaseCommand
{
    /** @var TcpLogger */
    private $logger;
    /** @var MysqlAdapter */
    private $db;
    /**@var ProductInterface[] */
    private $products = [];
    /**@var FileService*/
    private $fileService;
    /**@var ProductInterface[] */
    private $savedProducts = [];
    /**@var ProductInterface[] */
    private $productsHashMap = [];

    public function __construct(MysqlAdapter $db, LoggerAdapter $logger)
    {
        $this->logger = $logger;
        $this->db = $db;
    }

    protected function getSqlExistHashPattern():string
    {
        return 'select id,hash from product where hash in (%s)';
    }

    public function getDb(): MysqlAdapter
    {
        return $this->db;
    }

    /**
     * @param FileService $fileService
     * @return SaveProductImages
     */
    public function setFileService(FileService $fileService): SaveProductImages
    {
        $this->fileService = $fileService;
        return $this;
    }

    /**
     * @param ProductInterface[] $products
     * @return SaveProductImages
     */
    public function setProducts(array $products): SaveProductImages
    {
        $this->products  = $products;
        return $this;
    }

    /**
     * @param ProductInterface[] $productsHashMap
     * @return SaveProductImages
     */
    public function setProductsHashMap(array $productsHashMap): SaveProductImages
    {
        $this->productsHashMap = $productsHashMap;
        return $this;
    }

    public function save():bool
    {
        $this->savedProducts = [];
        $hashes = array_keys($this->productsHashMap);
        $existsHashes = $this->getExistHashes($hashes);
        $newProducts = array_diff_key($this->productsHashMap,$existsHashes);
        foreach ($newProducts as $product){
            /**@var ProductInterface $product*/
            $image = $product->getImage();
            $imageName = $product->getImageName();
            if(!empty($image) && !empty($imageName)){
                $this->fileService->setUserId($product->getUserId());
                if($this->fileService->saveProductImageSource($imageName,$image)){
                    $this->savedProducts[] = $product;
                }else{
                    return false;
                }
            }
        }
        return  true;
    }

    public function undo():void
    {
        if(empty($this->savedProducts)){
            return;
        }
        foreach ($this->savedProducts as $product){
            $this->fileService->setUserId($product->getUserId());
            $this->fileService->removeProductImage($product->getImageName());
        }
    }

}
