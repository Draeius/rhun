<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use App\Entity\Location\LocationEntity;

/**
 * Description of GuildHall
 *
 * @author Draeius
 * @Entity
 * @Table(name="guild_halls")
 */
class GuildHall {

    /**
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     *
     * @var LocationEntity[] 
     * @ManyToMany(targetEntity="App\Entity\Location\LocationEntity", cascade={"remove"})
     * @JoinTable(name="guild_rooms",
     *      joinColumns={@JoinColumn(name="guild_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="location_id", referencedColumnName="id", unique=true)}
     *      )
     */
    protected $locations;

    /**
     * 
     * @var LocationEntity
     * @OneToOne(targetEntity="App\Entity\Location\LocationEntity", cascade={"remove"}, fetch="EXTRA_LAZY")
     * @JoinColumn(name="location_id", referencedColumnName="id", unique=true)
     */
    protected $entrance;

    public function __construct() {
        $this->locations = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function addLocation(LocationEntity $location) {
        if(!$this->hasLocation($location)){
            $this->locations[] = $location;
        }
    }

    public function hasLocation(LocationEntity $check) {
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

    public function getEntrance(): LocationEntity {
        return $this->entrance;
    }

    public function setLocations($locations) {
        $this->locations = $locations;
    }

    public function setEntrance(LocationEntity $entrance) {
        $this->entrance = $entrance;
    }

}
