<?php

namespace App\Entity;

use App\Util\BasicEnum;

class Attribute extends BasicEnum {

    const STRENGTH = 0;
    const DEXTERITY = 1;
    const CONSTITUTION = 2;
    const INTELLIGENCE = 3;
    const WISDOM = 4;
    const WILLPOWER = 5;
    const HEALTH_POINTS = 20;
    const STAMINA_POINTS = 21;
    const MAGIC_POINTS = 22;

    public static function getName(int $attribute) {
        switch ($attribute) {
            case self::STRENGTH:
                return 'Stärke';
            case self::DEXTERITY:
                return 'Geschicklichkeit';
            case self::CONSTITUTION:
                return 'Konstitution';
            case self::INTELLIGENCE:
                return 'Intelligenz';
            case self::WISDOM:
                return 'Weisheit';
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

    public static function GET_ABILITY_MODIFIER(int $score): int {
        return floor(($score - 10) / 2);
    }

}
