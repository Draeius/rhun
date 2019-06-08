<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use AppBundle\Entity\AttributeWrapper;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of MonsterAttributes
 *
 * @author Draeius
 * @Entity
 * @Table(name="monster_attribute_templates")
 */
class MonsterAttributesTemplate extends AttributeWrapper {

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $weaponType;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $armorType;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $rank;

    public function getAttribute(int $attribute): int {
        return parent::getAttribute($attribute);
    }

    public function getWeaponType() {
        return $this->weaponType;
    }

    public function getArmorType() {
        return $this->armorType;
    }

    public function getRank() {
        return $this->rank;
    }

    public function setWeaponType($weaponType) {
        $this->weaponType = $weaponType;
    }

    public function setArmorType($armorType) {
        $this->armorType = $armorType;
    }

    public function setRank($rank) {
        $this->rank = $rank;
    }

}
