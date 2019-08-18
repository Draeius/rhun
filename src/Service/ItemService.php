<?php

namespace App\Service;

use App\Entity\Items\Armor;
use App\Entity\Items\ArmorTemplate;
use App\Entity\Items\Weapon;
use App\Entity\Items\WeaponTemplate;

/**
 * Description of ItemService
 *
 * @author Draeius
 */
class ItemService {

    public static function createWeapon(WeaponTemplate $template) {
        $weapon = new Weapon();
        $weapon->setWeaponTemplate($template);
        $weapon->setDescription($template->getDescription());
        $weapon->setPrice($template->getPrice());
        $weapon->setMadeByPlayer(true);
        $weapon->setName($template->getName());
        return $weapon;
    }

    public static function createArmor(ArmorTemplate $template) {
        $armor = new Armor();
        $armor->setArmorTemplate($template);
        $armor->setDescription($template->getDescription());
        $armor->setPrice($template->getPrice());
        $armor->setMadeByPlayer(true);
        $armor->setName($template->getName());
        return $armor;
    }

}
