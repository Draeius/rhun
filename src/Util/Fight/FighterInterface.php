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

    public function getAttributeDice(int $attribute): Dice;

    public function getAttackDice(): Dice;

    public function getDamage(): Damage;

    public function getArmorClass(): int;

    public function getAction(array $participants): ?Action;

    public function getVulnerabilitiers();

    public function getResistances();
}
