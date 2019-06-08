<?php

namespace App\Entity\Location;

use AppBundle\Util\Config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use NavigationBundle\Location\BookWritingLocation;

/**
 * Description of BookWritingLocationEntity
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_book_writing")
 */
class BookWritingLocationEntity extends LocationEntity {

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new BookWritingLocation($manager, $uuid, $this, $config);
    }

    public function getTemplate() {
        return 'locations/bookWritingLocation';
    }

}
