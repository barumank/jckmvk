<?php

namespace Backend\Models\MySQL\Models;

class Estimate extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    protected $object_id;

    /**
     *
     * @var integer
     */
    protected $type;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var integer
     */
    protected $status;

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
     * Method to set the value of field object_id
     *
     * @param integer $object_id
     * @return $this
     */
    public function setObjectId($object_id)
    {
        $this->object_id = $object_id;

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
     * Method to set the value of field status
     *
     * @param integer $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

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
     * Returns the value of field object_id
     *
     * @return integer
     */
    public function getObjectId()
    {
        return $this->object_id;
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
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("estimate");
        $this->hasMany('id', 'Backend\Models\MySQL\Models\ProductToEstimate', 'estimate_id', ['alias' => 'ProductToEstimate']);
        $this->hasMany('id', 'Backend\Models\MySQL\Models\ProductToEstimate', 'template_id', ['alias' => 'ProductToEstimate']);
        $this->hasMany('id', 'Backend\Models\MySQL\Models\ServiceToEstimate', 'estimate_id', ['alias' => 'ServiceToEstimate']);
        $this->hasMany('id', 'Backend\Models\MySQL\Models\ServiceToEstimate', 'template_id', ['alias' => 'ServiceToEstimate']);
        $this->belongsTo('object_id', 'Backend\Models\MySQL\Models\ObjectWork', 'id', ['alias' => 'ObjectWork']);
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
        return 'estimate';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Estimate[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Estimate
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
