<?php

namespace App\Util\Fight\Action;

use App\Util\Fight\FighterInterface;

/**
 * Repräsentiert eine Nahkampfattacke
 *
 * @author Draeius
 */
class AttackAction extends Action {

    public function execute(array &$participants): void {
        /* @var $target FighterInterface */
        $target = $participants[$this->getTargetIndex()];
        $atkDice = $this->getOrigin()->getAttackDice();

        $isHit = $atkDice->checkThreshold($target->getArmorClass());
        if ($isHit) {
            $participants[$this->getTargetIndex()]->doDamage($this->getOrigin()->getDamage());
        }
    }

}
