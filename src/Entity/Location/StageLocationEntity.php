<?php

namespace App\Entity\Location;

use AppBundle\Util\Config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use App\Entity\Location\PostableLocationEntity;
use NavigationBundle\Location\StageLocation;

/**
 * Description of StageLocationEntity
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_stage")
 */
class StageLocationEntity extends PostableLocationEntity {

    public function getTemplate() {
        return 'locations/stageLocation';
    }

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new StageLocation($manager, $uuid, $this, $config);
    }

}
