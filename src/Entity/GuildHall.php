<?php

namespace App\Entity;

use App\Entity\Location;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of GuildHall
 *
 * @author Draeius
 * @Entity
 * @Table(name="guild_halls")
 */
class GuildHall extends RhunEntity {

    /**
     *
     * @var LocationEntity[] 
     * @ManyToMany(targetEntity="Location", cascade={"remove"}, fetch="EXTRA_LAZY")
     * @JoinTable(name="guild_rooms",
     *      joinColumns={@JoinColumn(name="guild_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="location_id", referencedColumnName="id", unique=true)}
     *      )
     */
    protected $locations;

    /**
     * 
     * @var LocationEntity
     * @OneToOne(targetEntity="Location", cascade={"remove"}, fetch="EXTRA_LAZY")
     * @JoinColumn(name="location_id", referencedColumnName="id", unique=true)
     */
    protected $entrance;

    public function __construct() {
        $this->locations = new ArrayCollection();
    }

    public function addLocation(Location $location) {
        if (!$this->hasLocation($location)) {
            $this->locations[] = $location;
        }
    }

    public function hasLocation(Location $check) {
        foreach ($this->locations as $location) {
            if ($location->getId() == $check->getId()) {
                return true;
            }
        }
        return false;
    }

    public function getLocations() {
        return $this->locations;
    }

    public function getEntrance(): Location {
        return $this->entrance;
    }

    public function setLocations($locations) {
        $this->locations = $locations;
    }

    public function setEntrance(Location $entrance) {
        $this->entrance = $entrance;
    }

}
