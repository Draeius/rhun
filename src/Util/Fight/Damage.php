<?php

namespace App\Util\Fight;

use App\Entity\DamageType;

/**
 * Repräsentiert ausgeteilten Schaden jeglicher Art.
 *
 * @author Draeius
 */
class Damage {

    /**
     * Die Würfel, die diesen Schaden erzeugen
     * 
     * @var Dice
     */
    private $dice;

    /**
     * Der Schadenstyp den dieser Schaden anrichtet. 
     * 
     * @var DamageType
     */
    private $damageType;

    function __construct(Dice $dice, DamageType $damageType) {
        $this->dice = $dice;
        $this->damageType = $damageType;
    }

    /**
     * Berechnet den Schaden anhand eines Würfel und gegebenen Resistenzen und Verwundbarkeiten.
     * Wenn der Schadenstyp bereits in den Resistenzen enthalten ist, werden die Verwundbarkeiten nicht beachtet.
     * 
     * @param type $resistances Die Resistenzen die für dieses Schadensobjekt relevant sind
     * @param type $vulnerabilities Die Verwundbarkeiten die für dieses Schadensobjekt relevant sind 
     * @return int Der Schaden als integer. Mit eingerechnet sind Resistenzen und Verwundbarkeiten
     */
    public function getDamage($resistances, $vulnerabilities): int {
        foreach ($resistances as $res) {
            if ($res->getId() == $this->damageType->getId()) {
                return $this->dice->roll() / 2;
            }
        }
        foreach ($vulnerabilities as $vul) {
            if ($vul->getId() == $this->damageType->getId()) {
                return $this->dice->roll() * 2;
            }
        }
    }

    function getDice(): Dice {
        return $this->dice;
    }

    function getDamageType(): DamageType {
        return $this->damageType;
    }

}
