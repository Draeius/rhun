<?php

namespace App\Entity;

use App\Util\BasicEnum;

class ArmorType extends BasicEnum {

    const LIGHT = 0;
    const MIDDLE = 1;
    const HEAVY = 2;

    private static $DATA = [
        0 => [
            'armor' => 2,
            'evade' => 90,
            'name' => 'Leichte Rüstung'
        ],
        1 => [
            'armor' => 3,
            'evade' => 80,
            'name' => 'Mittlere Rüstung'
        ],
        2 => [
            'armor' => 4,
            'evade' => 50,
            'name' => 'Schwere Rüstung'
        ]
    ];

    private static function getData(int $type) {
        return self::$DATA[$type];
    }

    public static function getArmor(int $type) {
        return self::getData($type)['armor'];
    }

    public static function getEvade(int $type) {
        return self::getData($type)['evade'];
    }

    public static function getName(int $type) {
        return self::getData($type)['name'];
    }

}
