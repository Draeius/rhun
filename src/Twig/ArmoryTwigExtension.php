<?php

namespace App\Twig;

use App\Entity\ArmorType;
use App\Entity\Attribute;
use App\Entity\WeaponType;
use Twig_Extension;
use Twig_SimpleFilter;

class ArmoryTwigExtension extends Twig_Extension {

    public function getFilters() {
        return array(
            new Twig_SimpleFilter('weaponType', array($this, 'getWeaponType')),
            new Twig_SimpleFilter('armorType', array($this, 'getArmorType')),
            new Twig_SimpleFilter('attributeName', array($this, 'getAttributeName')),
            new Twig_SimpleFilter('isValidAttribute', array($this, 'isValidAttribute')),
            new Twig_SimpleFilter('damageInfo', array($this, 'getWeaponDamage'))
        );
    }

    public function getWeaponType($weaponType) {
        return WeaponType::getName($weaponType);
    }

    public function getArmorType($armorType) {
        return ArmorType::getName($armorType);
    }

    public function getAttributeName($attribute) {
        return Attribute::getName($attribute);
    }

    public function isValidAttribute($attribute) {
        return Attribute::isValidValue($attribute);
    }

    public function getWeaponDamage($weapon) {
        return round($weapon->getBaseDamage() - ($weapon->getBaseDamage() * WeaponType::getVariation($weapon->getWeaponType()) / 100))
                . '-' . round($weapon->getBaseDamage() + ($weapon->getBaseDamage() * WeaponType::getVariation($weapon->getWeaponType()) / 100));
    }

}
