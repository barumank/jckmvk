<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 03.04.19
 * Time: 16:01
 */

namespace Backend\Library\Service\SettingsService\TDO;


class AmountLimit
{
    protected $amountLimit;

    /**
     * Commission constructor.
     * @param array $params
     */
    public function __construct($params)
    {

        $this->amountLimit = 0;
        if (isset($params['amount_limit'])) {
            $this->amountLimit = $params['amount_limit'];
        }
    }

    /**
     * @return mixed
     */
    public function getAmountLimit()
    {
        return $this->amountLimit;
    }

    /**
     * @param mixed $amountLimit
     * @return AmountLimit
     */
    public function setAmountLimit($amountLimit)
    {
        $this->amountLimit = $amountLimit;
        return $this;
    }

    public function toArray()
    {
        return [
            'amount_limit' => (double)$this->amountLimit
        ];
    }
}