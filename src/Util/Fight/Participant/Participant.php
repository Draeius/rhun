<?php

namespace App\Util\Fight\Participant;

use App\Entity\Attribute;
use App\Util\Fight\Dice;
use App\Util\Fight\FighterInterface;

/**
 * Repräsentiert einen Teilnehmer in einem Kampf
 *
 * @author Draeius
 */
abstract class Participant {

    /**
     * Initiative des Teilnehmers
     * 
     * @var int
     */
    private $initiative;

    /**
     * Der Kämpfer, der an dem Kampf teilnimmt
     *
     * @var FighterInterface
     */
    private $fighter;

    function __construct(FighterInterface $fighter) {
        $d20 = new Dice(20, 1, $fighter->getAttribute(Attribute::DEXTERITY));
        $this->initiative = $d20->roll();

        $this->fighter = $fighter;
    }

    /**
     * Vergleicht die Initiative Werte von zwei Teilnehmern.
     * 
     * @param \App\Util\Fight\Participant $other
     * @return int
     */
    public function compareInitiative(Participant $other): int {
        if ($this->initiative == $other->initiative) {
            return 0;
        }
        return $this->initiative < $other->initiative;
    }

    public abstract function doDamage(Damage $damage);

    protected function getFighterId(): int {
        return $this->getFighter()->getId();
    }

    public function getInitiative(): int {
        return $this->initiative;
    }

    public function getFighter(): FighterInterface {
        return $this->fighter;
    }

    public function setFighter(FighterInterface $fighter) {
        $this->fighter = $fighter;
    }

    public function setInitiative(int $initiative) {
        $this->initiative = $initiative;
    }

    public abstract function getDataArray(): array;
}
