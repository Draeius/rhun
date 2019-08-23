<?php

namespace App\Util\Fight\Action;

use App\Entity\Attribute;
use App\Entity\Character;
use App\Util\Fight\FighterInterface;
use App\Util\Fight\Participant\Participant;

/**
 * Repräsentiert den Versuch aus einem Kampf zu fliehen
 *
 * @author Draeius
 */
class FleeAction extends Action {

    public function execute(array &$participants): void {
        if ($this->getOrigin() instanceof Character) {
            /* @var $value Participant */
            foreach ($participants as $key => $value) {
                if ($this->attemptFleeFrom($value)) {
                    unset($participants[$key]);
                }
            }
        } else {
            foreach ($participants as $key => $value) {
                if ($value instanceof Character && $this->attemptFleeFrom($value)) {
                    $this->deleteParticipant($this->getOrigin());
                }
            }
        }
    }

    private function deleteParticipant(FighterInterface $fighter, array &$participants) {
        foreach ($participants as $key => $value) {
            if ($value->getId() == $fighter->getId()) {
                unset($participants[$key]);
            }
        }
    }

    private function attemptFleeFrom(FighterInterface $fighter) {
        $otherDice = $fighter->getAttributeDice(Attribute::DEXTERITY);
        $ownDice = $this->getOrigin()->getAttributeDice(Attribute::DEXTERITY);

        return $ownDice->isGreaterThan($otherDice);
    }

}
