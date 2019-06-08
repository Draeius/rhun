<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity\Location;

use AppBundle\Util\Config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use NavigationBundle\Location\CraftingLocation;

/**
 * Description of KitchenLocationEntity
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_kitchen")
 */
class KitchenLocationEntity extends CraftingLocationEntity {

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new CraftingLocation($manager, $uuid, $this, $config);
    }

}
