<?php

namespace App\Entity;

use App\Util\BasicEnum;

class Attribute extends BasicEnum {

    const STRENGTH = 0;
    const PRECISION = 1;

    /**
     * Geschicklichkeit
     */
    const DEXTEROUSNESS = 2;
    const CONSTITUTION = 3;
    const AGILITY = 4;
    const INTELLIGENCE = 5;
    const WILLPOWER = 6;
    const HEALTH_POINTS = 20;
    const STAMINA_POINTS = 21;
    const MAGIC_POINTS = 22;

    public static function getName(int $attribute) {
        switch ($attribute) {
            case self::STRENGTH:
                return 'Stärke';
            case self::PRECISION:
                return 'Präzision';
            case self::DEXTEROUSNESS:
                return 'Geschicklichkeit';
            case self::CONSTITUTION:
                return 'Konstitution';
            case self::AGILITY:
                return 'Agilität';
            case self::INTELLIGENCE:
                return 'Intelligenz';
            case self::WILLPOWER:
                return 'Willenskraft';
            case self::HEALTH_POINTS:
                return 'Lebenspunkte';
            case self::STAMINA_POINTS:
                return 'Ausdauer';
            case self::MAGIC_POINTS:
                return 'Magie';
        }
    }

}
