<?php

namespace App\Entity\Location;

use AppBundle\Util\Config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use NavigationBundle\Location\RoomLocation;

/**
 * Description of RoomLocationEntity
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_room")
 */
class RoomLocationEntity extends PostableLocationEntity {

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new RoomLocation($manager, $uuid, $this, $config);
    }

}
