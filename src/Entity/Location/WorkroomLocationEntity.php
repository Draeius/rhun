<?php

namespace App\Entity\Location;

use AppBundle\Util\Config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use App\Entity\Location\LocationEntity;
use NavigationBundle\Location\WorkroomLocation;


/**
 * Description of WorkroomLocationEntity
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_workroom")
 */
class WorkroomLocationEntity extends LocationEntity {

    public function getTemplate() {
        return 'locations/workroom';
    }

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new WorkroomLocation($manager, $uuid, $this, $config);
    }

}
