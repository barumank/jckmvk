<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 05.04.19
 * Time: 13:20
 */

namespace Backend\Library\Service\SettingsService\TDO;


class Tax
{
    /**
     * @var double
     */
    protected $tax;

    /**
     * Tax constructor.
     * @param array $params
     */
    public function __construct($params)
    {

        $this->tax = 5;
        if (isset($params['tax'])) {
            $this->tax = $params['tax'];
        }
    }


    /**
     * @return float
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * @param float $tax
     * @return Tax
     */
    public function setTax($tax)
    {
        $this->tax = $tax;
        return $this;
    }

    public function toArray()
    {
        return [
            'tax' => (double)$this->tax
        ];
    }
}