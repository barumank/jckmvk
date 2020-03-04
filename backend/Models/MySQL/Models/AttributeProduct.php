<?php

namespace Backend\Models\MySQL\Models;

class AttributeProduct extends \Phalcon\Mvc\Model
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
    protected $group_id;

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
     * @var integer
     */
    protected $order;

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
     * Method to set the value of field group_id
     *
     * @param integer $group_id
     * @return $this
     */
    public function setGroupId($group_id)
    {
        $this->group_id = $group_id;

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
     * Method to set the value of field order
     *
     * @param integer $order
     * @return $this
     */
    public function setOrder($order)
    {
        $this->order = $order;

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
     * Returns the value of field group_id
     *
     * @return integer
     */
    public function getGroupId()
    {
        return $this->group_id;
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
     * Returns the value of field order
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("attribute_product");
        $this->hasMany('id', 'Backend\Models\MySQL\Models\ProductAnalog', 'product_attribute_id', ['alias' => 'ProductAnalog']);
        $this->hasMany('id', 'Backend\Models\MySQL\Models\ProductAnalog', 'analog_attribute_id', ['alias' => 'ProductAnalog']);
        $this->hasMany('id', 'Backend\Models\MySQL\Models\ProductToAttribute', 'attribute_id', ['alias' => 'ProductToAttribute']);
        $this->belongsTo('group_id', 'Backend\Models\MySQL\Models\AttributeGroup', 'id', ['alias' => 'AttributeGroup']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'attribute_product';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AttributeProduct[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AttributeProduct
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
