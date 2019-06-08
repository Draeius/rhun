<?php

namespace App\Entity\Location;

use AppBundle\Util\Config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use App\Entity\Location\PostableLocationEntity;
use NavigationBundle\Location\GraveyardLocation;

/**
 * Description of GraveyardLocationEntity
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_graveyard")
 */
class GraveyardLocationEntity extends PostableLocationEntity {

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new GraveyardLocation($manager, $uuid, $this, $config);
    }

    public function getTemplate() {
        return 'locations/graveyardLocation';
    }

}
