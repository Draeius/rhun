<?php

namespace App\Util\Fight\Participant;

use App\Util\Fight\Dice;
use App\Util\Fight\FighterInterface;

/**
 * Ein monster, das an einem Kampf teilnimmt.
 *
 * @author Draeius
 */
class MonsterParticipant extends Participant {

    /**
     * Die Lebenspunkte des Monsters
     *
     * @var int
     */
    private $currentHP;

    public function __construct(FighterInterface $fighter) {
        parent::__construct($fighter);
        $this->fighterId = $fighter->getId();
    }

    function getCurrentHP() {
        return $this->currentHP;
    }

    function setCurrentHP($currentHP) {
        $this->currentHP = $currentHP;
    }

    public function doDamage(Dice $damageDice) {
        $this->currentHP -= $damageDice->roll();
    }

    public function serialize(): string {
        json_encode([
            'class' => self::class,
            'currentHP' => $this->currentHP,
            'fighterId' => $this->getFighterId(),
            'initiative' => $this->getInitiative()
        ]);
    }

    public function unserialize($serialized): void {
        $data = json_decode($serialized);
        $this->setInitiative($data['initiative']);
        $this->currentHP = $data['currentHP'];
    }

}
