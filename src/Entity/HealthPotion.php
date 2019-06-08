<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * @author Draeius
 * @Entity
 * @Table(name="health_potions")
 */
class HealthPotion extends Item {

    /**
     *
     * @var number
     * @Column(type="integer")
     */
    protected $restoreHP;

    /**
     *
     * @var number
     * @Column(type="integer")
     */
    protected $restoreStamina;

    public function __toString() {
        return $this->getName() . ' (HP: ' . $this->restoreHP . '; A: ' . $this->restoreStamina . ')';
    }

    public function getRestoreHP() {
        return $this->restoreHP;
    }

    public function getRestoreStamina() {
        return $this->restoreStamina;
    }

    public function setRestoreHP($restoreHP) {
        $this->restoreHP = $restoreHP;
    }

    public function setRestoreStamina($restoreStamina) {
        $this->restoreStamina = $restoreStamina;
    }
    
    public function getDisplayTemplate(): string {
        return 'parts/potion.html.twig';
    }


}
