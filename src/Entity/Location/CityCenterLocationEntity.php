<?php

namespace App\Entity\Location;

use AppBundle\Util\Config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use App\Entity\Location\PostableLocationEntity;
use NavigationBundle\Location\CityCenter;

/**
 * Description of CityCenterLocationEntity
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_city_center")
 */
class CityCenterLocationEntity extends PostableLocationEntity {

    public function getTemplate() {
        return 'locations/cityCenter';
    }

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new CityCenter($manager, $uuid, $this, $config);
    }

}
