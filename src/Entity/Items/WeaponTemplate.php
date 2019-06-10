<?php

namespace App\Entity\Items;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * A Weapon that can be used by the player
 *
 * @author Draeius
 * @Entity(repositoryClass="App\Repository\WeaponTemplateRepository")
 * @Table(name="templates_weapon")
 */
class WeaponTemplate extends Item {

    /**
     *
     * @var int The weapons damage base
     * @Column(type="float", nullable=false)
     */
    protected $baseDamage = 0;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $weaponType = 0;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $attribute = 0;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $minAttribute = 10;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $staminaDrain = 1;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $level = 1;

    public function getBaseDamage(): float {
        return $this->baseDamage;
    }

    public function getWeaponType(): int {
        return $this->weaponType;
    }

    public function getAttribute(): int {
        return $this->attribute;
    }

    public function getMinAttribute(): int {
        return $this->minAttribute;
    }

    public function getStaminaDrain(): int {
        return $this->staminaDrain;
    }

    public function getLevel(): int {
        return $this->level;
    }

    public function setLevel(int $level) {
        $this->level = $level;
    }

    public function setBaseDamage(float $baseDamage) {
        $this->baseDamage = $baseDamage;
    }

    public function setWeaponType(int $weaponType) {
        $this->weaponType = $weaponType;
    }

    public function setAttribute(int $attribute) {
        $this->attribute = $attribute;
    }

    public function setMinAttribute(int $minAttribute) {
        $this->minAttribute = $minAttribute;
    }

    public function setStaminaDrain(int $staminaDrain) {
        $this->staminaDrain = $staminaDrain;
    }

    function getDisplayTemplate() {
        return 'parts/weapon.html.twig';
    }

}
