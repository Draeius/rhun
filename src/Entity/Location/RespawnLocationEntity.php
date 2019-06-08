<?php

namespace App\Entity\Location;

use AppBundle\Util\Config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use App\Entity\Location\PostableLocationEntity;
use NavigationBundle\Location\RespawnLocation;

/**
 * Description of RespawnLocationEntity
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_respawn")
 */
class RespawnLocationEntity extends PostableLocationEntity {

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new RespawnLocation($manager, $uuid, $this, $config);
    }

}
