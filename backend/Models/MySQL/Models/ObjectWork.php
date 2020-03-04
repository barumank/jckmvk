<?php

namespace Backend\Models\MySQL\Models;

class ObjectWork extends \Phalcon\Mvc\Model
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
    protected $root_organization_id;

    /**
     *
     * @var integer
     */
    protected $directory_id;

    /**
     *
     * @var integer
     */
    protected $user_id;

    /**
     *
     * @var integer
     */
    protected $client_id;

    /**
     *
     * @var integer
     */
    protected $foreman_id;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var double
     */
    protected $amount;

    /**
     *
     * @var string
     */
    protected $square;

    /**
     *
     * @var integer
     */
    protected $floor;

    /**
     *
     * @var integer
     */
    protected $radiators;

    /**
     *
     * @var string
     */
    protected $warm_floor;

    /**
     *
     * @var string
     */
    protected $boiler_room_power;

    /**
     *
     * @var string
     */
    protected $address;

    /**
     *
     * @var string
     */
    protected $image;

    /**
     *
     * @var string
     */
    protected $images;

    /**
     *
     * @var integer
     */
    protected $is_delete;

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
     * Method to set the value of field directory_id
     *
     * @param integer $directory_id
     * @return $this
     */
    public function setDirectoryId($directory_id)
    {
        $this->directory_id = $directory_id;

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
     * Method to set the value of field client_id
     *
     * @param integer $client_id
     * @return $this
     */
    public function setClientId($client_id)
    {
        $this->client_id = $client_id;

        return $this;
    }

    /**
     * Method to set the value of field foreman_id
     *
     * @param integer $foreman_id
     * @return $this
     */
    public function setForemanId($foreman_id)
    {
        $this->foreman_id = $foreman_id;

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
     * Method to set the value of field square
     *
     * @param string $square
     * @return $this
     */
    public function setSquare($square)
    {
        $this->square = $square;

        return $this;
    }

    /**
     * Method to set the value of field floor
     *
     * @param integer $floor
     * @return $this
     */
    public function setFloor($floor)
    {
        $this->floor = $floor;

        return $this;
    }

    /**
     * Method to set the value of field radiators
     *
     * @param integer $radiators
     * @return $this
     */
    public function setRadiators($radiators)
    {
        $this->radiators = $radiators;

        return $this;
    }

    /**
     * Method to set the value of field warm_floor
     *
     * @param string $warm_floor
     * @return $this
     */
    public function setWarmFloor($warm_floor)
    {
        $this->warm_floor = $warm_floor;

        return $this;
    }

    /**
     * Method to set the value of field boiler_room_power
     *
     * @param string $boiler_room_power
     * @return $this
     */
    public function setBoilerRoomPower($boiler_room_power)
    {
        $this->boiler_room_power = $boiler_room_power;

        return $this;
    }

    /**
     * Method to set the value of field address
     *
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

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
     * Method to set the value of field images
     *
     * @param string $images
     * @return $this
     */
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * Method to set the value of field is_delete
     *
     * @param integer $is_delete
     * @return $this
     */
    public function setIsDelete($is_delete)
    {
        $this->is_delete = $is_delete;

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
     * Returns the value of field root_organization_id
     *
     * @return integer
     */
    public function getRootOrganizationId()
    {
        return $this->root_organization_id;
    }

    /**
     * Returns the value of field directory_id
     *
     * @return integer
     */
    public function getDirectoryId()
    {
        return $this->directory_id;
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
     * Returns the value of field client_id
     *
     * @return integer
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * Returns the value of field foreman_id
     *
     * @return integer
     */
    public function getForemanId()
    {
        return $this->foreman_id;
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
     * Returns the value of field amount
     *
     * @return double
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Returns the value of field square
     *
     * @return string
     */
    public function getSquare()
    {
        return $this->square;
    }

    /**
     * Returns the value of field floor
     *
     * @return integer
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * Returns the value of field radiators
     *
     * @return integer
     */
    public function getRadiators()
    {
        return $this->radiators;
    }

    /**
     * Returns the value of field warm_floor
     *
     * @return string
     */
    public function getWarmFloor()
    {
        return $this->warm_floor;
    }

    /**
     * Returns the value of field boiler_room_power
     *
     * @return string
     */
    public function getBoilerRoomPower()
    {
        return $this->boiler_room_power;
    }

    /**
     * Returns the value of field address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
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
     * Returns the value of field images
     *
     * @return string
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Returns the value of field is_delete
     *
     * @return integer
     */
    public function getIsDelete()
    {
        return $this->is_delete;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("object_work");
        $this->hasMany('id', 'Backend\Models\MySQL\Models\Estimate', 'object_id', ['alias' => 'Estimate']);
        $this->hasMany('id', 'Backend\Models\MySQL\Models\ObjectFile', 'object_id', ['alias' => 'ObjectFile']);
        $this->belongsTo('client_id', 'Backend\Models\MySQL\Models\Client', 'id', ['alias' => 'Client']);
        $this->belongsTo('directory_id', 'Backend\Models\MySQL\Models\Directory', 'id', ['alias' => 'Directory']);
        $this->belongsTo('foreman_id', 'Backend\Models\MySQL\Models\Foreman', 'id', ['alias' => 'Foreman']);
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
        return 'object_work';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ObjectWork[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ObjectWork
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
