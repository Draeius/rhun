<?php

namespace App\Entity;

use App\Util\BasicEnum;

class ArmorType extends BasicEnum {

    const LIGHT = 0;
    const MIDDLE = 1;
    const HEAVY = 2;

    private static $DATA = [
        0 => 'Leichte Rüstung',
        1 => 'Mittlere Rüstung',
        2 => 'Schwere Rüstung'
    ];

    public static function getName(int $type) {
        return self::$DATA[$type];
    }

}
