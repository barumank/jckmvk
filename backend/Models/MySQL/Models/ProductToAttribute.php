<?php

namespace Backend\Models\MySQL\Models;

class ProductToAttribute extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $attribute_id;

    /**
     *
     * @var integer
     */
    protected $product_id;

    /**
     *
     * @var string
     */
    protected $value;

    /**
     *
     * @var string
     */
    protected $hash;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field attribute_id
     *
     * @param integer $attribute_id
     * @return $this
     */
    public function setAttributeId($attribute_id)
    {
        $this->attribute_id = $attribute_id;

        return $this;
    }

    /**
     * Method to set the value of field product_id
     *
     * @param integer $product_id
     * @return $this
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;

        return $this;
    }

    /**
     * Method to set the value of field value
     *
     * @param string $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Method to set the value of field hash
     *
     * @param string $hash
     * @return $this
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field attribute_id
     *
     * @return integer
     */
    public function getAttributeId()
    {
        return $this->attribute_id;
    }

    /**
     * Returns the value of field product_id
     *
     * @return integer
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * Returns the value of field value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns the value of field hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {

        $this->setSource("product_to_attribute");
        $this->belongsTo('attribute_id', 'Backend\Models\MySQL\Models\AttributeProduct', 'id', ['alias' => 'AttributeProduct']);
        $this->belongsTo('product_id', 'Backend\Models\MySQL\Models\Product', 'id', ['alias' => 'Product']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'product_to_attribute';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProductToAttribute[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProductToAttribute
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
