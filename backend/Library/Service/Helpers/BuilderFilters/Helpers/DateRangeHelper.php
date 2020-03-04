<?php

namespace Backend\Library\Service\Helpers\BuilderFilters\Helpers;


class DateRangeHelper
{
    const DATE_FILTER_FORMAT = 'Y-m-d';

    public function getTodayRange()
    {
        $date = date(self::DATE_FILTER_FORMAT);
        return [
            'start' => $date,
            'end' => $date
        ];
    }

    public function getYesterdayRange()
    {
        $date = (new \DateTime())
            ->sub(new \DateInterval('P1D'))
            ->format(self::DATE_FILTER_FORMAT);
        return [
            'start' => $date,
            'end' => $date,
        ];
    }

    public function getCurrentWeekRange()
    {
        $start = (new \DateTime())->sub(new \DateInterval('P' . abs((7 - date("N") - 7)) . 'D'));
        $end = (new \DateTime());

        return [
            'start' => $start->format(self::DATE_FILTER_FORMAT),
            'end' => $end->format(self::DATE_FILTER_FORMAT),
        ];
    }

    public function getCurrentMonthRange()
    {
        $start = (new \DateTime())->modify('first day of this month');
        $end = (new \DateTime());

        return [
            'start' => $start->format(self::DATE_FILTER_FORMAT),
            'end' => $end->format(self::DATE_FILTER_FORMAT),
        ];
    }

    public function getPreviousMonthRange()
    {
        $start = (new \DateTime())->modify('first day of previous month');
        $end = (new \DateTime())->modify('last day of previous month');

        return [
            'start' => $start->format(self::DATE_FILTER_FORMAT),
            'end' => $end->format(self::DATE_FILTER_FORMAT),
        ];
    }

    public function getCurrentYearRange()
    {
        $start = date(self::DATE_FILTER_FORMAT, strtotime(date('Y-01-01 00:00:00')));
        $end = date(self::DATE_FILTER_FORMAT);

        return [
            'start' => $start,
            'end' => $end,
        ];
    }

}