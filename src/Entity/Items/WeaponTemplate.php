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
     * @Column(type="string", nullable=false)
     */
    protected $damage = '1d4';

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
     * Gibt an, ob diese Waffe als dualwield geeignet ist
     * 
     * @var bool
     * @Column(type="boolean")
     */
    protected $offhand = false;

    /**
     * Gibt an, ob man mit dieser Waffe/Schild eine Zweite in der anderen Hand führen kann
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $offhandAllowed = false;

    /**
     * Der Verteidigungsbonus, den diese Waffe/Schild bringt, wenn man sie in als dualwield benutzt
     *
     * @var int
     * @Column(type="boolean")
     */
    protected $offhandDefBonus = 0;

    /**
     * Der Angriffsbonus, den diese Waffe/Schild bringt, wenn man sie in als dualwield benutzt
     *
     * @var int
     * @Column(type="string")
     */
    protected $offhandAtkBonus = '0d0';

    /**
     *
     * @var DamageType
     * @ManyToOne(targetEntity="DamageType")
     * @JoinColumn(name="damage_type_id", referencedColumnName="id")
     */
    protected $damageType;

    public function getDamage(): int {
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

    function getOffhand() {
        return $this->offhand;
    }

    function getOffhandAllowed() {
        return $this->offhandAllowed;
    }

    function getOffhandDefBonus() {
        return $this->offhandDefBonus;
    }

    function getOffhandAtkBonus() {
        return $this->offhandAtkBonus;
    }

    function getDamageType(): DamageType {
        return $this->damageType;
    }

    public function setDamage(float $baseDamage) {
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

    function setOffhand($offhand) {
        $this->offhand = $offhand;
    }

    function setOffhandAllowed($offhandAllowed) {
        $this->offhandAllowed = $offhandAllowed;
    }

    function setOffhandDefBonus($offhandDefBonus) {
        $this->offhandDefBonus = $offhandDefBonus;
    }

    function setOffhandAtkBonus($offhandAtkBonus) {
        $this->offhandAtkBonus = $offhandAtkBonus;
    }

    function setDamageType(DamageType $damageType) {
        $this->damageType = $damageType;
    }

    function getDisplayTemplate() {
        return 'parts/weapon.html.twig';
    }

}
