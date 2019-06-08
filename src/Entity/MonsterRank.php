<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use AppBundle\Util\BasicEnum;

/**
 * Description of MonsterRank
 *
 * @author Draeius
 */
class MonsterRank extends BasicEnum {

    const EASY = 0;
    const NORMAL = 1;
    const HARD = 2;
    const PLAYER_LIKE = 3;
    const ELITE = 4;

    public static function getHpModifier(int $rank) {
        switch ($rank) {
            case self::EASY:
                return 0.1;
            case self::NORMAL:
                return 0.3;
            case self::HARD:
                return 0.5;
            case self::PLAYER_LIKE:
                return 1;
            case self::ELITE:
                return 1.5;
        }
    }

}
