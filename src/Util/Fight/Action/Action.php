<?php

namespace App\Util\Fight\Action;

use App\Util\Fight\FighterInterface;

/**
 * Repräsetiert die Aktion, die der Spieler ausführt
 *
 * @author Draeius
 */
abstract class Action {

    /**
     *
     * @var FighterInterface
     */
    private $origin;

    /**
     * Der Index des Teilnehmers, der Ziel dieser Aktion ist.
     *
     * @var int
     */
    private $targetIndex;

    /**
     * Die Verzögerung in Runden. Z.B. Zeit, die ein Zauberer zum casten braucht.
     * 
     * @var int
     */
    private $delay;

    public function __construct(FighterInterface $origin, int $targetIndex, int $delay) {
        $this->targetIndex = $targetIndex;
        $this->origin = $origin;
    }

    /**
     * Führt die Aktion aus.
     * 
     * @param Participants[] $participants Die Liste aller Teilnehmer des Kampfes
     */
    public abstract function execute(array &$participants): void;

    /**
     * Reduziert die Wartezeit um eine Runde
     * 
     * @return void
     */
    public function reduceDelay(): void {
        $this->delay --;
    }

    /**
     * Gibt an, ob diese Aktion bereits ausgeführt wurde.
     * 
     * @return bool
     */
    public function isExecuted(): bool {
        return $this->delay <= -1;
    }

    /**
     * Gibt an, ob die Aktion fertig zum ausführen ist
     * 
     * @return bool
     */
    public function isReady(): bool {
        return $this->delay <= 0;
    }

    function getDelay(): int {
        return $this->delay;
    }

    function getTargetIndex(): int {
        return $this->targetIndex;
    }

    function getOrigin(): FighterInterface {
        return $this->origin;
    }

}
