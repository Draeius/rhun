<?php

namespace App\Util\Fight;

use App\Util\BasicEnum;

/**
 * Gibt an, ob bei einem Wurf ein Vorteil, Nachteil oder keins von beidem besteht.
 *
 * @author Draeius
 */
class DiceMode extends BasicEnum {

    const ADVANTAGE = 0;
    const NONE = 1;
    const DISADVANTAGE = 2;

}
