<?php

namespace App\Entity\Location;

use AppBundle\Util\Config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use NavigationBundle\Location\CraftingLocation;

/**
 * Description of CraftingLocationEntity
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_crafting")
 */
class CraftingLocationEntity extends PostableLocationEntity {

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $efficiency = 50;

    public function getTemplate() {
        return 'locations/craftingLocation';
    }

    public function getEfficiency() {
        return $this->efficiency;
    }

    public function setEfficiency($efficiency) {
        $this->efficiency = $efficiency;
    }

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new CraftingLocation($manager, $uuid, $this, $config);
    }

}
