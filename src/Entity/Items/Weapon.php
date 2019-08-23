<?php

namespace App\Entity\Items;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="weapons")
 */
class Weapon extends Item {

    /**
     *
     * @var WeaponTemplate
     * @ManyToOne(targetEntity="WeaponTemplate")
     * @JoinColumn(name="weapon_template_id", referencedColumnName="id")
     */
    protected $weaponTemplate;
    protected $buffs;

    function getWeaponTemplate(): WeaponTemplate {
        return $this->weaponTemplate;
    }

    function getBuffs() {
        return $this->buffs;
    }

    function setWeaponTemplate(WeaponTemplate $weaponTemplate) {
        $this->weaponTemplate = $weaponTemplate;
    }

    function setBuffs($buffs) {
        $this->buffs = $buffs;
    }

    function getDisplayTemplate() {
        return 'parts/weapon.html.twig';
    }

}
