<?php

namespace Backend\Models\MySQL\Models;

class ProductSimilarGroup extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $product_id;

    /**
     *
     * @var integer
     */
    public $analog_id;

    /**
     *
     * @var integer
     */
    public $group_ip;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("mvk_crm");
        $this->setSource("product_similar_group");
        $this->belongsTo('group_ip', 'Backend\Models\MySQL\Models\AttributeGroup', 'id', ['alias' => 'AttributeGroup']);
        $this->belongsTo('product_id', 'Backend\Models\MySQL\Models\Product', 'id', ['alias' => 'Product']);
        $this->belongsTo('analog_id', 'Backend\Models\MySQL\Models\Product', 'id', ['alias' => 'ProductAnalog']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'product_similar_group';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProductSimilarGroup[]|ProductSimilarGroup|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProductSimilarGroup|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->product_id;
    }

    /**
     * @param int $product_id
     * @return $this
     */
    public function setProductId(int $product_id)
    {
        $this->product_id = $product_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getAnalogId(): int
    {
        return $this->analog_id;
    }

    /**
     * @param int $analog_id
     * @return $this
     */
    public function setAnalogId(int $analog_id)
    {
        $this->analog_id = $analog_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getGroupIp(): int
    {
        return $this->group_ip;
    }

    /**
     * @param int $group_ip
     * @return $this
     */
    public function setGroupIp(int $group_ip)
    {
        $this->group_ip = $group_ip;

        return $this;
    }

}
