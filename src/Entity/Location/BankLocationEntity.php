<?php

namespace App\Entity\Location;

use AppBundle\Util\Config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use App\Entity\Location\PostableLocationEntity;
use NavigationBundle\Location\BankLocation;

/**
 * Description of BankLocationEntity
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_bank")
 */
class BankLocationEntity extends PostableLocationEntity {

    public function getTemplate() {
        return 'locations/bankLocation';
    }

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new BankLocation($manager, $uuid, $this, $config);
    }

}
