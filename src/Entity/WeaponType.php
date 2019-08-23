<?php

namespace App\Entity;

use App\Util\BasicEnum;

/**
 * Description of WeaponType
 *
 * @author Draeius
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
    const STAFF = 8;
    const FOCUS = 9;
    const SHIELD = 10;

    private static $DATA = [
        0 => 'Dolch',
        1 => 'Schwert',
        2 => 'Streitkolben',
        3 => 'Bogen',
        4 => 'Kriegshammer',
        5 => 'Streitaxt',
        6 => 'Stangenwaffe',
        7 => 'Zweihänder',
        8 => 'Stab',
        9 => 'Fokus',
        10 => 'Schild'
    ];

    public static function getName(int $type) {
        return self::$DATA[$type];
    }

}
