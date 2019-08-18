<?php

namespace App\Util;

use App\Service\DateTimeService;

/**
 * Description of Season
 *
 * @author Draeius
 */
class Season extends BasicEnum {

    const SPRING = 1;
    const SUMMER = 2;
    const FALL = 3;
    const WINTER = 4;

    public static function getSeasonByDate() {
        $date = DateTimeService::getDateTime('NOW');
        $compare = $date->format('nd');
        if ($compare < 320) {
            //date is smaller than 320. Thus it is before march 20, thus it is winter.
            return self::WINTER;
        }
        if ($compare < 621) {
            //date smaller than june 21 => spring
            return self::SPRING;
        }
        if ($compare < 922) {
            //date smaller than september 22 => summer
            return self::SUMMER;
        }
        if ($compare < 1221) {
            //date smaller than december 22 => fall
            return self::FALL;
        }
        //date greater than december 21 => winter
        return self::WINTER;
    }

}
