<?php

namespace App\Util\Fight\Participant;

/**
 * Ein Charakter der an einem Kampf teilnimmt.
 *
 * @author Draeius
 */
class CharacterParticipant extends Participant {

    public function serialize(): string {
        return json_encode([
            'class' => self::class,
            'fighterId' => $this->getFighterId(),
            'initiative' => $this->getInitiative()
        ]);
    }

    public function doDamage(Dice $damageDice) {
        $this->getFighter()->addHP(-1 * $damageDice->roll());
    }

    public function unserialize($serialized): void {
        $data = json_decode($serialized);
        $this->setInitiative($data['initiative']);
    }

}
