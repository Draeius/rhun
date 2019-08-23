<?php

namespace App\Util\Fight\Participant;

use App\Entity\Monster;
use App\Util\Fight\Damage;
use App\Util\Fight\FighterInterface;

/**
 * Ein monster, das an einem Kampf teilnimmt.
 *
 * @author Draeius
 */
class MonsterParticipant extends Participant {

    public function __construct(FighterInterface $fighter) {
        parent::__construct($fighter);
        $this->fighterId = $fighter->getId();
    }

    function getCurrentHP() {
        /* @var $monster Monster */
        $monster = $this->getFighter();
        return $monster->getCurrentHP();
    }

    function setCurrentHP($currentHP) {
        /* @var $monster Monster */
        $monster = $this->getFighter();
        return $monster->setCurrentHP($currentHP);
    }

    public function doDamage(Damage $damage) {
        $hp = $this->getCurrentHP() - $damage->getDamage($this->getFighter()->getResistances(), $this->getFighter()->getVulnerabilitiers());
        $this->setCurrentHP($hp);
    }

    public function getDataArray(): array {
        return [
            'class' => self::class,
            'currentHP' => $this->getCurrentHP(),
            'fighterId' => $this->getFighterId(),
            'initiative' => $this->getInitiative()
        ];
    }

}
