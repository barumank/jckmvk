<?php

namespace Backend\Library\Parsers\SDK;

use Backend\Library\Parsers\SDK\Command\SaveCategories;
use Backend\Library\Parsers\SDK\Command\SaveProductAttributes;
use Backend\Library\Parsers\SDK\Command\SaveProductImages;
use Backend\Library\Parsers\SDK\Command\SaveProducts;
use Backend\Library\Parsers\SDK\Command\SaveProductCategories;
use Backend\Library\Parsers\SDK\Interfaces\Product as ProductInterface;
use Backend\Library\Phalcon\Db\MysqlAdapter;
use Backend\Library\Phalcon\Logger\TcpLogger;
use Backend\Library\Service\Auth\Exception;
use Phalcon\Di\FactoryDefault;
use Phalcon\Logger\Adapter as LoggerAdapter;
use \Backend\Library\Service\FileService\Manager as FileService;

class Manager
{
    /**@var ProductInterface[] */
    private $products = [];
    /**@var ProductInterface[] */
    /** @var TcpLogger */
    private $logger;
    /** @var MysqlAdapter */
    private $db;
    /**@var SaveCategories */
    private $saveCategoriesCommand;
    /**@var SaveProducts */
    private $saveProductsCommand;
    /**@var SaveProductCategories */
    private $saveProductCategoriesCommand;
    /**@var SaveProductAttributes */
    private $saveProductAttributesCommand;
    /**@var FileService */
    private $fileService;
    /**@var SaveProductImages */
    private $saveProductImageCommand;

    /**
     * CategoryHelper constructor.
     * @param LoggerAdapter $logger
     */
    public function __construct(LoggerAdapter $logger)
    {
        $di = FactoryDefault::getDefault();
        $this->logger = $logger;
        $this->db = $di->get('db');
        $this->fileService = $di->get('fileService');
        $this->saveCategoriesCommand = new SaveCategories($this->db, $logger);
        $this->saveProductsCommand = new SaveProducts($this->db, $logger);
        $this->saveProductCategoriesCommand = new SaveProductCategories($this->db, $logger);
        $this->saveProductAttributesCommand = new SaveProductAttributes($this->db, $logger);
        $this->saveProductImageCommand = (new SaveProductImages($this->db, $logger))
            ->setFileService($this->fileService);

    }

    /**
     * @return ProductInterface[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param ProductInterface[] $products
     * @return Manager
     */
    public function setProducts(array $products): Manager
    {
        $this->saveProductsCommand->setProducts($products);
        $products = $this->saveProductsCommand->getProducts();
        $this->saveCategoriesCommand->setProducts($products);
        $this->saveProductCategoriesCommand->setProducts($products);
        $this->saveProductAttributesCommand->setProducts($products);
        $this->saveProductImageCommand
            ->setProductsHashMap($this->saveProductsCommand->getProductHashMap())
            ->setProducts($products);
        $this->products = $products;
        return $this;
    }

    public function save()
    {
        try {
            if (!$this->saveProductImageCommand->save()) {
                $this->undo();
                return false;
            }
            $this->db->ping();
            $this->db->begin();

            if (!$this->saveCategoriesCommand->save()) {
                $this->undo();
                return false;
            }
            if (!$this->saveProductsCommand->save()) {
                $this->undo();
                return false;
            }
            if (!$this->saveProductCategoriesCommand->save()) {
                $this->undo();
                return false;
            }
            if (!$this->saveProductAttributesCommand->save()) {
                $this->undo();
                return false;
            }
            $this->db->commit();
        } catch (\Exception $exception) {
            $this->undo();
            return false;
        }
        return true;
    }

    private function undo(): void
    {
        if ($this->db->isUnderTransaction()) {
            $this->db->rollback();
        }
        $this->saveProductImageCommand->undo();
    }

}
