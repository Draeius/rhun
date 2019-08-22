<?php

namespace App\Util\Fight;

use App\Entity\Character;
use App\Util\Fight\Action\Action;
use App\Util\Fight\Participant\Participant;
use App\Util\Fight\Participant\ParticipantFactory;
use Serializable;

/**
 * Description of Fight
 *
 * @author Draeius
 */
class Fight implements Serializable {

    /**
     * Liste aller Teilnehmer dieses Kampfes
     * 
     * @var Participants
     */
    private $participants = [];

    public function executeAction(Action &$action): void {
        do {
            $this->doRound($action);
        } while (!$action->isExecuted());
    }

    /**
     * Simuliert eine Runde in diesem Kampf
     * 
     * @return void
     */
    public function doRound(Action &$action): void {
        $count = count($this->participants);
        for ($index = 0; $index < $count; $index++) {
            if (!($this->participants[$index] instanceof Character)) {
                $this->participants[$index]->getAction($this->participants)->execute($this->participants);
            } elseif ($action->isReady()) {
                $action->execute($this->participants);
            }
        }
        $action->reduceDelay();
    }

    /**
     * Etabliert die Reihenfolge in der die Kämpfer angreifen
     * 
     * @return void
     */
    public function establishFightOrder(): void {
        usort($this->participants, function(Participant $a, Participant $b) {
            return $a->compareInitiative($b);
        });
    }

    /**
     * Fügt dem Kampf einen Kämpfer hinzu
     * 
     * @param FighterInterface $fighter
     */
    public function addFighter(FighterInterface $fighter): void {
        $this->addParticipant(ParticipantFactory::FACTORY($fighter));
    }

    /**
     * Fügt dem Kampf einen Teilnehmer hinzu
     * 
     * @param Participant $participant
     * @return void
     */
    public function addParticipant(Participant $participant): void {
        array_push($this->participants, $participant);
    }

    /**
     * Gibt alle Charaktere die in diesem Kampf beteiligt sind als Array zurück
     * 
     * @return array
     */
    public function getCharacterParticipants(): array {
        $result = [];
        foreach ($this->participants as $fighter) {
            if ($fighter instanceof Character) {
                $result[] = $fighter;
            }
        }
        return $result;
    }

    public function serialize(): string {
        $result = [];
        foreach ($this->participants as $key => $value) {
            $result[$key] = json_encode($value->serialize());
        }
        return json_encode($result);
    }

    public function unserialize($serialized): void {
        $this->participants = json_decode($serialized);
    }

}
