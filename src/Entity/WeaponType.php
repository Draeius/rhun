<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use App\Util\BasicEnum;

/**
 * Description of WeaponType
 *
 * @author Matthias
 */
class WeaponType extends BasicEnum {

    const DAGGER = 0;
    const SWORD = 1;
    const MACE = 2;
    const BOW = 3;
    const HAMMER = 4;
    const AXE = 5;
    const POLEARM = 6;
    const TWO_HANDED_SWORD = 7;

    private static $DATA = [
        0 => [
            'variation' => 5,
            'str_mult' => 1,
            'ges_mult' => 1.5,
            'crit_mult' => 2.2,
            'name' => 'Dolch'
        ],
        1 => [
            'variation' => 10,
            'str_mult' => 0.45,
            'ges_mult' => 4.4,
            'crit_mult' => 0.25,
            'name' => 'Schwert'
        ],
        2 => [
            'variation' => 20,
            'str_mult' => 3.8,
            'ges_mult' => 2.2,
            'crit_mult' => 0.15,
            'name' => 'Streitkolben'
        ],
        3 => [
            'variation' => 5,
            'str_mult' => 1.1,
            'ges_mult' => 0.9,
            'crit_mult' => 1.75,
            'name' => 'Bogen'
        ],
        4 => [
            'variation' => 25,
            'str_mult' => 3,
            'ges_mult' => 0.8,
            'crit_mult' => 0.05,
            'name' => 'Kriegshammer'
        ],
        5 => [
            'variation' => 15,
            'str_mult' => 2.42,
            'ges_mult' => 1.4,
            'crit_mult' => 0.25,
            'name' => 'Streitaxt'
        ],
        6 => [
            'variation' => 5,
            'str_mult' => 0.9,
            'ges_mult' => 1.15,
            'crit_mult' => 1.5,
            'name' => 'Stangenwaffe'
        ],
        7 => [
            'variation' => 10,
            'str_mult' => 1.95,
            'ges_mult' => 2.4,
            'crit_mult' => 0.2,
            'name' => 'Zweihänder'
        ]
    ];

    private static function getData(int $type) {
        return self::$DATA[$type];
    }

    public static function getVariation(int $type) {
        if (self::isValidValue($type)) {
            return self::getData($type)['variation'];
        }
        return null;
    }

    public static function getStrMult(int $type) {
        if (self::isValidValue($type)) {
            return self::getData($type)['str_mult'];
        }
        return null;
    }

    public static function getGesMult(int $type) {
        if (self::isValidValue($type)) {
            return self::getData($type)['ges_mult'];
        }
        return null;
    }

    public static function getCritMult(int $type) {
        if (self::isValidValue($type)) {
            return self::getData($type)['crit_mult'];
        }
        return null;
    }

    public static function getName(int $type) {
        return self::getData($type)['name'];
    }

}
