<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 03.06.19
 * Time: 16:03
 */

namespace Backend\Library\Service\SettingsService\TDO;


class Contacts
{
    /**
     * @var string|null
     */
    protected $infoEmail;
    /**
     * @var string|null
     */
    protected $infoPhone;
    /**
     * @var string|null
     */
    protected $address;

    /**
     * Commission constructor.
     * @param array $params
     */
    public function __construct($params)
    {

        $this->infoEmail = '';
        if (isset($params['infoEmail'])) {
            $this->infoEmail = $params['infoEmail'];
        }

        $this->infoPhone = '';
        if (isset($params['infoPhone'])) {
            $this->infoPhone = $params['infoPhone'];
        }

        $this->address = '';
        if (isset($params['address'])) {
            $this->address = $params['address'];
        }

    }

    public function toArray()
    {
        return [
            'infoEmail' => $this->infoEmail,
            'infoPhone' => $this->infoPhone,
            'address' => $this->address
        ];
    }

    /**
     * @return string|null
     */
    public function getInfoEmail(): ?string
    {
        return $this->infoEmail;
    }

    /**
     * @param string|null $infoEmail
     * @return Contacts
     */
    public function setInfoEmail(?string $infoEmail): Contacts
    {
        $this->infoEmail = $infoEmail;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getInfoPhone(): ?string
    {
        return $this->infoPhone;
    }

    /**
     * @param string|null $infoPhone
     * @return Contacts
     */
    public function setInfoPhone(?string $infoPhone): Contacts
    {
        $this->infoPhone = $infoPhone;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     * @return Contacts
     */
    public function setAddress(?string $address): Contacts
    {
        $this->address = $address;
        return $this;
    }

}