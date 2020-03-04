<?php

namespace Backend\Library\Service\SettingsService\TDO;

class PercentUser
{
    /**
     * @var double
     */
    protected $percent;

    /**
     * PercentUserAmount constructor.
     * @param array $params
     */
    public function __construct($params)
    {

        $this->percent = 5;
        if (isset($params['percent'])) {
            $this->percent = $params['percent'];
        }
    }


    /**
     * @return float
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @param float $percent
     * @return PercentUser
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;
        return $this;
    }

    public function toArray()
    {
        return [
            'commission' => (double)$this->percent
        ];
    }

}