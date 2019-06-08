<?php

namespace App\Entity\Location;

use AppBundle\Util\Config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use NavigationBundle\Location\PostOfficeLocation;

/**
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_postOffice")
 */
class PostOfficeLocationEntity extends PostableLocationEntity {

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new PostOfficeLocation($manager, $uuid, $this, $config);
    }

    public function getTemplate() {
        return 'locations/postOfficeLocation';
    }

}
