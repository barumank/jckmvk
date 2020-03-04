<?php

namespace Backend\Models\MySQL\Models;

class Service extends \Phalcon\Mvc\Model
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
    protected $user_id;

    /**
     *
     * @var integer
     */
    protected $root_organization_id;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $unit;

    /**
     *
     * @var double
     */
    protected $price;

    /**
     *
     * @var string
     */
    protected $customer_margin;

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
     * Method to set the value of field root_organization_id
     *
     * @param integer $root_organization_id
     * @return $this
     */
    public function setRootOrganizationId($root_organization_id)
    {
        $this->root_organization_id = $root_organization_id;

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
     * Method to set the value of field unit
     *
     * @param string $unit
     * @return $this
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Method to set the value of field price
     *
     * @param double $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Method to set the value of field customer_margin
     *
     * @param string $customer_margin
     * @return $this
     */
    public function setCustomerMargin($customer_margin)
    {
        $this->customer_margin = $customer_margin;

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
     * Returns the value of field root_organization_id
     *
     * @return integer
     */
    public function getRootOrganizationId()
    {
        return $this->root_organization_id;
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
     * Returns the value of field unit
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Returns the value of field price
     *
     * @return double
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Returns the value of field customer_margin
     *
     * @return string
     */
    public function getCustomerMargin()
    {
        return $this->customer_margin;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("service");
        $this->hasMany('id', 'Backend\Models\MySQL\Models\ServiceToEstimate', 'service_id', ['alias' => 'ServiceToEstimate']);
        $this->belongsTo('root_organization_id', 'Backend\Models\MySQL\Models\Organization', 'id', ['alias' => 'Organization']);
        $this->belongsTo('user_id', 'Backend\Models\MySQL\Models\User', 'id', ['alias' => 'User']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'service';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Service[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Service
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
