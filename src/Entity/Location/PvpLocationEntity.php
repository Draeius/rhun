<?php

namespace App\Entity\Location;

use AppBundle\Util\Config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use App\Entity\Location\LocationEntity;
use NavigationBundle\Location\PvpLocation;

/**
 * Description of PvpLocationEntity
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_pvp")
 */
class PvpLocationEntity extends LocationEntity {

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new PvpLocation($manager, $uuid, $this, $config);
    }

    public function getTemplate() {
        return 'locations/pvpLocation.html.twig';
    }

}
