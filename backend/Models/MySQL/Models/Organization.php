<?php

namespace Backend\Models\MySQL\Models;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class Organization extends \Phalcon\Mvc\Model
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
    protected $root_id;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $inn;

    /**
     *
     * @var string
     */
    protected $legal_address;

    /**
     *
     * @var string
     */
    protected $postal_address;

    /**
     *
     * @var string
     */
    protected $correspondent_account;

    /**
     *
     * @var string
     */
    protected $payment_account;

    /**
     *
     * @var string
     */
    protected $name_director;

    /**
     *
     * @var string
     */
    protected $position_director;

    /**
     *
     * @var string
     */
    protected $by_virtue;

    /**
     *
     * @var string
     */
    protected $image;

    /**
     *
     * @var string
     */
    protected $email;

    /**
     *
     * @var string
     */
    protected $phone;

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
     * Method to set the value of field root_id
     *
     * @param integer $root_id
     * @return $this
     */
    public function setRootId($root_id)
    {
        $this->root_id = $root_id;

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
     * Method to set the value of field inn
     *
     * @param string $inn
     * @return $this
     */
    public function setInn($inn)
    {
        $this->inn = $inn;

        return $this;
    }

    /**
     * Method to set the value of field legal_address
     *
     * @param string $legal_address
     * @return $this
     */
    public function setLegalAddress($legal_address)
    {
        $this->legal_address = $legal_address;

        return $this;
    }

    /**
     * Method to set the value of field postal_address
     *
     * @param string $postal_address
     * @return $this
     */
    public function setPostalAddress($postal_address)
    {
        $this->postal_address = $postal_address;

        return $this;
    }

    /**
     * Method to set the value of field correspondent_account
     *
     * @param string $correspondent_account
     * @return $this
     */
    public function setCorrespondentAccount($correspondent_account)
    {
        $this->correspondent_account = $correspondent_account;

        return $this;
    }

    /**
     * Method to set the value of field payment_account
     *
     * @param string $payment_account
     * @return $this
     */
    public function setPaymentAccount($payment_account)
    {
        $this->payment_account = $payment_account;

        return $this;
    }

    /**
     * Method to set the value of field name_director
     *
     * @param string $name_director
     * @return $this
     */
    public function setNameDirector($name_director)
    {
        $this->name_director = $name_director;

        return $this;
    }

    /**
     * Method to set the value of field position_director
     *
     * @param string $position_director
     * @return $this
     */
    public function setPositionDirector($position_director)
    {
        $this->position_director = $position_director;

        return $this;
    }

    /**
     * Method to set the value of field by_virtue
     *
     * @param string $by_virtue
     * @return $this
     */
    public function setByVirtue($by_virtue)
    {
        $this->by_virtue = $by_virtue;

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
     * Method to set the value of field phone
     *
     * @param string $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

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
     * Returns the value of field root_id
     *
     * @return integer
     */
    public function getRootId()
    {
        return $this->root_id;
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
     * Returns the value of field inn
     *
     * @return string
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * Returns the value of field legal_address
     *
     * @return string
     */
    public function getLegalAddress()
    {
        return $this->legal_address;
    }

    /**
     * Returns the value of field postal_address
     *
     * @return string
     */
    public function getPostalAddress()
    {
        return $this->postal_address;
    }

    /**
     * Returns the value of field correspondent_account
     *
     * @return string
     */
    public function getCorrespondentAccount()
    {
        return $this->correspondent_account;
    }

    /**
     * Returns the value of field payment_account
     *
     * @return string
     */
    public function getPaymentAccount()
    {
        return $this->payment_account;
    }

    /**
     * Returns the value of field name_director
     *
     * @return string
     */
    public function getNameDirector()
    {
        return $this->name_director;
    }

    /**
     * Returns the value of field position_director
     *
     * @return string
     */
    public function getPositionDirector()
    {
        return $this->position_director;
    }

    /**
     * Returns the value of field by_virtue
     *
     * @return string
     */
    public function getByVirtue()
    {
        return $this->by_virtue;
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
     * Returns the value of field email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns the value of field phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("organization");
        $this->hasMany('id', 'Backend\Models\MySQL\Models\Estimate', 'root_organization_id', ['alias' => 'Estimate']);
        $this->hasMany('id', 'Backend\Models\MySQL\Models\ObjectWork', 'root_organization_id', ['alias' => 'ObjectWork']);
        $this->hasMany('id', 'Backend\Models\MySQL\Models\Service', 'root_organization_id', ['alias' => 'Service']);
        $this->hasMany('id', 'Backend\Models\MySQL\Models\UserToOrganization', 'organization_id', ['alias' => 'UserToOrganization']);
        $this->belongsTo('user_id', 'Backend\Models\MySQL\Models\User', 'id', ['alias' => 'User']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'organization';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Organization[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Organization
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
