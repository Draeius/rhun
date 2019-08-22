<?php

namespace App\Util\Fight;

use App\Util\Fight\Action\Action;

/**
 * Definiert Methoden für Kämpfer in einem Kampf
 *
 * @author Draeius
 */
interface FighterInterface {

    public function getId(): ?int;

    public function getMaxHP(): int;

    public function getAttribute(int $attribute): int;

    public function getAttackDice(): Dice;

    public function getDamageDice(): Dice;

    public function getArmorClass(): int;

    public function getAction(array $participants): ?Action;
}
