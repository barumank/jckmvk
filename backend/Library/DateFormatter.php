<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 23.04.19
 * Time: 16:06
 */

namespace Backend\Library;


class DateFormatter
{
    const DAY_NAME_MAP = [1 => 'Mo', 2 => 'Tu', 3 => 'We', 4 => 'Th', 5 => 'Fr', 6 => 'Sa', 7 => 'Su'];


    const FORMAT_USER = 'jS F, Y g:i a';
    const FORMAT_PARTNER_FEEDBACK = 'd.m.Y';
    const FORMAT_DEFAULT = 'Y-m-d H:i:s';
    const FORMAT_TIME = 'h:i A';
    const FORMAT_ORDER_HISTORY = 'd.m.Y, h:i A';
    const FORMAT_ORDER_COMMENT = 'd.m.Y \a\t h:i A';
    const FORMAT_USER_ORDER_DETAILS = 'd.m.y';
    const FORMAT_PARTNER_REVIEWS = 'F d, Y';
    const FORMAT_PARTNER_ORDER = 'D, d.m.Y';
    const FORMAT__ORDER_ACCEPTED = '\!\D, F, d h:i A';

    /**
     * @var string
     */
    private $date;
    /**
     * @var string
     */
    private $format;

    private function customFormat($format,$time)
    {
        if(strpos($format,'!D')!==false){
            $date = (int) date('N',$time);
            $format =  str_replace('!D',self::DAY_NAME_MAP[$date],$format);
        }
        return $format;
    }

    public function formatted(): string
    {
        if (is_string($this->date)) {
            $time = strtotime($this->date);
        } else {
            $time = $this->date;
        }

        $date = date($this->format,$time);

        return $this->customFormat($date,$time);
    }

    /**
     * @return string|int
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string|int $date
     * @return DateFormatter
     */
    public function setDate($date): DateFormatter
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @return DateFormatter
     */
    public function setFormat(string $format): DateFormatter
    {
        $this->format = $format;
        return $this;
    }


}