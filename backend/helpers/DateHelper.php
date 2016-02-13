<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 13.02.2016
 * Time: 14:28
 */

namespace backend\helpers;


class DateHelper
{
    static $month_name = [
        1=>'Январь',
        2=>'Февраль',
        3=>'Март',
        4=>'Апрель',
        5=>'Май',
        6=>'Июнь',
        7=>'Июль',
        8=>'Август',
        9=>'Сентябрь',
        10=>'Октябрь',
        11=>'Ноябрь',
        12=>'Декабрь'
    ];

    static function format($date_str)
    {
        $year = date('Y', $date_str);
        $month = self::$month_name[date('n', $date_str)];
        $day = date('j', $date_str);
        $hour = date('H', $date_str);
        $minute = date('i', $date_str);
        $second = date('s', $date_str);

        if ($year == date('Y')) {
            $year = '';
        } else {
            $year = "({$year}г.) ";
        }
        return "\"{$day}\" {$month} {$year} ({$hour}:{$minute})";
    }
}