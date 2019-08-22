<?php

namespace App\Util\Fight;

/**
 * Repräsentiert einen Satz Würfel mit gleichen Augenzahlen.
 *
 * @author Draeius
 */
class Dice {

    /**
     * Wie viele Würfel geworfen werden
     * @var int
     */
    private $count;

    /**
     * Welche Augenzahl die Würfel haben
     * @var int
     */
    private $number;

    /**
     * Um was das Ergebnis modifiziert wird
     * @var int
     */
    private $modificator;

    public function __construct(int $number, int $count = 1, int $modificator = 0) {
        $this->count = $count;
        $this->number = $number;
        $this->modificator = $modificator;
    }

    /**
     * Addiert den gegebenen Wert zum Modifikator hinzu.
     * @param int $amount
     */
    public function addToModificator(int $amount) {
        $this->modificator += $amount;
    }

    /**
     * Multipliziert den Modifikator mit dem gegebenen Wert.
     * @param float $factor
     */
    public function multiplyModificator(float $factor) {
        $this->modificator *= $factor;
    }

    /**
     * Simuliert den Wurf dieser Würfel.
     * 
     * @return int Das Ergebnis des Wurfes
     */
    public function roll(): int {
        $value = 0;
        for ($i = 0; $i < $this->count; $i++) {
            $value += round(mt_rand(1, $this->number));
        }
        return $value + $this->modificator;
    }

    /**
     * Vergleicht zwei Würfe unterschiedlicher Würfel mit einander.
     *
     * @param \App\Util\Dice $other Der Würfel mit dem verglichen wird.
     * @param int $mode Gibt an, ob bei diesem Wurf ein Vorteil, Nachteil oder keins von beidem besteht.
     * @see App\Util\Dice\DiceMode
     * 
     * @return bool Gibt true zurück, wenn der eigene Wurf größer oder gleich dem Wurf des anderen Würfels ist, sonst false.
     */
    public function isGreaterThan(Dice $other, int $mode = DiceMode::NONE): bool {
        return $this->checkThreshold($other->roll(), $mode);
    }

    /**
     * Vergleicht den Würf dieses Würfels mit einem gegebenen Limit.
     * 
     * @param int $threshold Das Limit, dass der Wurf nicht überschreiten darf.
     * @param int $mode Gibt an, ob bei diesem Wurf ein Vorteil, Nachteil oder keins von beidem besteht.
     * @see App\Util\Dice\DiceMode
     * 
     * @return bool Gibt true zurück, wenn der Wurf größer oder gleich dem Limit war, sonst false.
     */
    public function checkThreshold(int $threshold, int $mode = DiceMode::NONE): bool {
        switch ($mode) {
            case DiceMode::ADVANTAGE:
                $valOne = $this->roll();
                $valTwo = $this->roll();
                if ($valOne > $valTwo) {
                    return $valOne >= $threshold;
                }
                return $valTwo >= $threshold;
            case DiceMode::DISADVANTAGE:
                $valOne = $this->roll();
                $valTwo = $this->roll();
                if ($valOne > $valTwo) {
                    return $valTwo >= $threshold;
                }
                return $valOne >= $threshold;
        }
        return $this->roll() >= $threshold;
    }

    /**
     * Generiert einen neuen Würfel
     * 
     * @param string|array|null $dice Strings müssen die Form 2d12+3 haben, wobei +3 optional ist. Arrays werden als 0 => Anzahl Würfel, 1 => Augenzahl, 2 => Modifikator gewertet.
     * null generiert einen 6-seitigen Würfel ohne Modifikator
     */
    public static function FACTORY($dice): Dice {
        if (is_array($dice)) {
            return new Dice($dice[1], $dice[0], $dice[2]);
        }
        if (is_string($dice)) {
            return self::FACTORY(self::DECODE_DICE_ROLL($dice));
        }
        return new Dice(6);
    }

    /**
     * Dekodiert den Wurf eines Würfels.
     * 
     * @param string $roll Der String der den Wurf repräsentiert muss die Form 2d12+3 haben, wobei +3 optional ist.
     * @return array
     */
    public static function DECODE_DICE_ROLL(string $roll): array {
        $preg = '/[+-]*\d+/';
        $matches = [];
        preg_match_all($preg, $roll, $matches);
        $dice = $matches[0];
        if (!array_key_exists(2, $dice)) {
            $dice[2] = 0;
        }
        return $dice;
    }

}
