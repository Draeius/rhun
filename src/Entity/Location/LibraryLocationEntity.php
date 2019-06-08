<?php

namespace App\Entity\Location;

use AppBundle\Util\Config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use App\Entity\Location\LocationEntity;
use NavigationBundle\Location\LibraryLocation;

/**
 * Description of LibraryLocationEntity
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_library")
 */
class LibraryLocationEntity extends LocationEntity {

    public function getTemplate() {
        return 'locations/libraryLocation';
    }

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new LibraryLocation($manager, $uuid, $this, $config);
    }

}
