<?php

namespace App\Service;

/**
 * Description of DiceService
 *
 * @author Draeius
 */
class DiceService {

    public static function rollDice(int $times, int $dice, int $mod) {
        $value = 0;
        for ($i = 0; $i < $times; $i++) {
            $value += round(mt_rand(1, $dice));
        }
        return $value + $mod;
    }

    public static function checkDice(int $times, int $dice, int $mod, int $threshold) {
        return self::rollDice($times, $dice, $mod) <= $threshold;
    }

}
