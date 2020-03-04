<?php

namespace Backend\Models\MySQL\Models;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class Client extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $last_name;

    /**
     *
     * @var string
     */
    protected $first_phone;

    /**
     *
     * @var string
     */
    protected $second_phone;

    /**
     *
     * @var string
     */
    protected $email;

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
     * Method to set the value of field last_name
     *
     * @param string $last_name
     * @return $this
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * Method to set the value of field first_phone
     *
     * @param string $first_phone
     * @return $this
     */
    public function setFirstPhone($first_phone)
    {
        $this->first_phone = $first_phone;

        return $this;
    }

    /**
     * Method to set the value of field second_phone
     *
     * @param string $second_phone
     * @return $this
     */
    public function setSecondPhone($second_phone)
    {
        $this->second_phone = $second_phone;

        return $this;
    }

    /**
     * Method to set the value of field email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

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
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field last_name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Returns the value of field first_phone
     *
     * @return string
     */
    public function getFirstPhone()
    {
        return $this->first_phone;
    }

    /**
     * Returns the value of field second_phone
     *
     * @return string
     */
    public function getSecondPhone()
    {
        return $this->second_phone;
    }

    /**
     * Returns the value of field email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("client");
        $this->hasMany('id', 'Backend\Models\MySQL\Models\ObjectWork', 'client_id', ['alias' => 'ObjectWork']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'client';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Client[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Client
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
