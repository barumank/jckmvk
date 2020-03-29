<?php

namespace Backend\Models\MySQL\Models;

class Product extends \Phalcon\Mvc\Model
{
    /**
     * Типы товаров
     */
    const TYPE_BASE = 0;
    const TYPE_CUSTOM = 1;
    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $user_id;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $vendor_code;

    /**
     *
     * @var double
     */
    protected $rrc;

    /**
     *
     * @var string
     */
    protected $discount;

    /**
     *
     * @var double
     */
    protected $amount;

    /**
     *
     * @var string
     */
    protected $image;

    /**
     *
     * @var integer
     */
    protected $type;

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
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field vendor_code
     *
     * @param string $vendor_code
     * @return $this
     */
    public function setVendorCode($vendor_code)
    {
        $this->vendor_code = $vendor_code;

        return $this;
    }

    /**
     * Method to set the value of field rrc
     *
     * @param double $rrc
     * @return $this
     */
    public function setRrc($rrc)
    {
        $this->rrc = $rrc;

        return $this;
    }

    /**
     * Method to set the value of field discount
     *
     * @param string $discount
     * @return $this
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Method to set the value of field amount
     *
     * @param double $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Method to set the value of field image
     *
     * @param string $image
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Method to set the value of field type
     *
     * @param integer $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

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
     * Returns the value of field user_id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field vendor_code
     *
     * @return string
     */
    public function getVendorCode()
    {
        return $this->vendor_code;
    }

    /**
     * Returns the value of field rrc
     *
     * @return double
     */
    public function getRrc()
    {
        return $this->rrc;
    }

    /**
     * Returns the value of field discount
     *
     * @return string
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Returns the value of field amount
     *
     * @return double
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Returns the value of field image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Returns the value of field type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
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
        $this->setSource("product");
        $this->hasMany('id', 'Backend\Models\MySQL\Models\ProductAnalog', 'product_id', ['alias' => 'ProductAnalog']);
        $this->hasMany('id', 'Backend\Models\MySQL\Models\ProductAnalog', 'analog_id', ['alias' => 'ProductAnalog']);
        $this->hasMany('id', 'Backend\Models\MySQL\Models\ProductSimilarGroup', 'product_id', ['alias' => 'ProductSimilarGroup']);
        $this->hasMany('id', 'Backend\Models\MySQL\Models\ProductSimilarGroup', 'analog_id', ['alias' => 'ProductSimilarGroup']);
        $this->hasMany('id', 'Backend\Models\MySQL\Models\ProductToAttribute', 'product_id', ['alias' => 'ProductToAttribute']);
        $this->hasMany('id', 'Backend\Models\MySQL\Models\ProductToCategory', 'product_id', ['alias' => 'ProductToCategory']);
        $this->hasMany('id', 'Backend\Models\MySQL\Models\ProductToEstimate', 'product_id', ['alias' => 'ProductToEstimate']);
        $this->belongsTo('user_id', 'Backend\Models\MySQL\Models\User', 'id', ['alias' => 'User']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'product';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Product[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Product
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
