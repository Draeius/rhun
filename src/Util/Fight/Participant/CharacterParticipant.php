<?php

namespace App\Util\Fight\Participant;

use App\Util\Fight\Damage;

/**
 * Ein Charakter der an einem Kampf teilnimmt.
 *
 * @author Draeius
 */
class CharacterParticipant extends Participant {

    public function doDamage(Damage $damage) {
        $this->getFighter()->addHP(-1 * $damage->getDamage($this->getFighter()->getResistances(), $this->getFighter()->getVulnerabilitiers()));
    }

    public function getDataArray(): array {
        return [
            'class' => self::class,
            'fighterId' => $this->getFighterId(),
            'initiative' => $this->getInitiative()
        ];
    }

}
