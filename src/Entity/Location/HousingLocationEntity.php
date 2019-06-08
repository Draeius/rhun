<?php

namespace App\Entity\Location;

use AppBundle\Util\Config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use HouseBundle\Entity\House;
use App\Entity\Location\Area;
use App\Entity\Location\PostableLocationEntity;
use NavigationBundle\Location\HousingLocation;

/**
 * Description of HousingLocationEntity
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_housing")
 */
class HousingLocationEntity extends PostableLocationEntity {

    /**
     *
     * @var House[]
     * @OneToMany(targetEntity="HouseBundle\Entity\House", mappedBy="location", fetch="EXTRA_LAZY")
     */
    protected $houses;

    /**
     *
     * @var Area
     * @OneToOne(targetEntity="Area", fetch="EXTRA_LAZY")
     */
    protected $houseArea;

    public function getTemplate() {
        return 'locations/housingLocation';
    }

    public function getHouses() {
        return $this->houses;
    }

    public function getHouseArea() {
        return $this->houseArea;
    }

    public function setHouseArea(Area $houseArea) {
        $this->houseArea = $houseArea;
    }

    public function setHouses(House $houses) {
        $this->houses = $houses;
    }

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new HousingLocation($manager, $uuid, $this, $config);
    }

}
