<?php

namespace App\Entity\Location;

use AppBundle\Util\Config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use App\Entity\Location\LocationEntity;
use NavigationBundle\Location\StockExchangeLocation;


/**
 * Description of StockExchangeLocationEntity
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_stock")
 */
class StockExchangeLocationEntity extends LocationEntity {

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new StockExchangeLocation($manager, $uuid, $this, $config);
    }

    public function getTemplate() {
        return 'locations/stockExchangeLocation';
    }

}
