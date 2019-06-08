<?php

namespace App\Entity\Location;

use AppBundle\Util\Config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use App\Entity\Location\PostableLocationEntity;
use NavigationBundle\Location\Hallway;

/**
 * Description of HallwayLocationEntity
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_hallway")
 */
class HallwayLocationEntity extends PostableLocationEntity {

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new Hallway($manager, $uuid, $this, $config);
    }

    public function getTemplate(): string {
        return 'locations/hallway';
    }

}
