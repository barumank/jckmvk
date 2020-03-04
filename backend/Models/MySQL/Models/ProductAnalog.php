<?php

namespace Backend\Models\MySQL\Models;

class ProductAnalog extends \Phalcon\Mvc\Model
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
    protected $product_id;

    /**
     *
     * @var integer
     */
    protected $analog_id;

    /**
     *
     * @var integer
     */
    protected $user_id;

    /**
     *
     * @var integer
     */
    protected $product_attribute_id;

    /**
     *
     * @var integer
     */
    protected $analog_attribute_id;

    /**
     *
     * @var integer
     */
    protected $bind_type;

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
     * Method to set the value of field analog_id
     *
     * @param integer $analog_id
     * @return $this
     */
    public function setAnalogId($analog_id)
    {
        $this->analog_id = $analog_id;

        return $this;
    }

    /**
     * Method to set the value of field user_id
     *
     * @param integer $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Method to set the value of field product_attribute_id
     *
     * @param integer $product_attribute_id
     * @return $this
     */
    public function setProductAttributeId($product_attribute_id)
    {
        $this->product_attribute_id = $product_attribute_id;

        return $this;
    }

    /**
     * Method to set the value of field analog_attribute_id
     *
     * @param integer $analog_attribute_id
     * @return $this
     */
    public function setAnalogAttributeId($analog_attribute_id)
    {
        $this->analog_attribute_id = $analog_attribute_id;

        return $this;
    }

    /**
     * Method to set the value of field bind_type
     *
     * @param integer $bind_type
     * @return $this
     */
    public function setBindType($bind_type)
    {
        $this->bind_type = $bind_type;

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
     * Returns the value of field product_id
     *
     * @return integer
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * Returns the value of field analog_id
     *
     * @return integer
     */
    public function getAnalogId()
    {
        return $this->analog_id;
    }

    /**
     * Returns the value of field user_id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Returns the value of field product_attribute_id
     *
     * @return integer
     */
    public function getProductAttributeId()
    {
        return $this->product_attribute_id;
    }

    /**
     * Returns the value of field analog_attribute_id
     *
     * @return integer
     */
    public function getAnalogAttributeId()
    {
        return $this->analog_attribute_id;
    }

    /**
     * Returns the value of field bind_type
     *
     * @return integer
     */
    public function getBindType()
    {
        return $this->bind_type;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("product_analog");
        $this->belongsTo('product_id', 'Backend\Models\MySQL\Models\Product', 'id', ['alias' => 'Product']);
        $this->belongsTo('analog_id', 'Backend\Models\MySQL\Models\Product', 'id', ['alias' => 'Product']);
        $this->belongsTo('product_attribute_id', 'Backend\Models\MySQL\Models\AttributeProduct', 'id', ['alias' => 'AttributeProduct']);
        $this->belongsTo('analog_attribute_id', 'Backend\Models\MySQL\Models\AttributeProduct', 'id', ['alias' => 'AttributeProduct']);
        $this->belongsTo('user_id', 'Backend\Models\MySQL\Models\User', 'id', ['alias' => 'User']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'product_analog';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProductAnalog[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProductAnalog
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
