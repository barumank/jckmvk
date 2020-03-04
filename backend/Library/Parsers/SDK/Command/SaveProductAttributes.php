<?php


namespace Backend\Library\Parsers\SDK\Command;


use Backend\Library\Parsers\SDK\Interfaces\Attribute as AttributeInterface;
use Backend\Library\Parsers\SDK\Interfaces\Product as ProductInterface;
use Backend\Library\Phalcon\Db\MysqlAdapter;
use Backend\Library\Phalcon\Logger\TcpLogger;
use Phalcon\Logger\Adapter as LoggerAdapter;

class SaveProductAttributes  extends BaseCommand
{
    /** @var TcpLogger */
    private $logger;
    /** @var MysqlAdapter */
    private $db;
    /**@var ProductInterface[] */
    private $products = [];
    /**@var AttributeInterface[]*/
    private $attributeHashMap;

    public function __construct(MysqlAdapter $db, LoggerAdapter $logger)
    {
        $this->logger = $logger;
        $this->db = $db;
    }

    protected function getSqlExistHashPattern():string
    {
        return 'select attribute_id,product_id,hash from product_to_attribute where hash in (%s)';
    }

    public function getDb(): MysqlAdapter
    {
        return $this->db;
    }

    /**
     * @param ProductInterface[] $products
     * @return SaveProductAttributes
     */
    public function setProducts(array $products): SaveProductAttributes
    {
        $this->products = $products;
        return $this;
    }

    public function save(): bool
    {
       $this->initAttributeHashMap();
        $hashes = array_keys($this->attributeHashMap);
        $this->db->ping();
        $existsHashes = $this->getExistHashes($hashes);
        $newAttributes = array_diff_key($this->attributeHashMap, $existsHashes);
        $newAttributesChunk = array_chunk($newAttributes, 1000);
        foreach ($newAttributesChunk as $chunk) {
            if (!$this->insert($chunk)) {
                return false;
            }
        }
        return true;
    }

    public function initAttributeHashMap():void
    {
       $this->attributeHashMap = [];
        foreach ($this->products as $product){
            $attributes = $product->getAttributes();
            if(empty($attributes)){
                continue;
            }
            foreach ($attributes as $attribute) {
                $attribute->setProductId($product->getId());
                $this->attributeHashMap[$attribute->hash()] = $attribute;
            }
        }
    }

    /**
     * @param AttributeInterface[] $attributes
     * @return bool
     */
    private function insert($attributes)
    {
        if (empty($attributes)) {
            return true;
        }
        $db = $this->db;
        $insert = [];
        foreach ($attributes as $attribute) {
            $productId = $attribute->getProductId();
            $attributeId = $attribute->getAttributeId();
            $value = $db->escapeString($attribute->getValue());
            $hash = $db->escapeString($attribute->hash());
            $insert[] = "({$attributeId},{$productId},{$value},{$hash})";
        }
        $sql = 'insert into product_to_attribute(attribute_id,product_id,`value`,hash)values' . implode(',' . PHP_EOL, $insert) . ';';
        return $db->execute($sql);
    }


}
