<?php

namespace App\Entity;

use App\Entity\Area;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use App\Entity\Location\LocationEntity;

/**
 * Represents a race
 *
 * @author Draeius
 * @Entity(repositoryClass="App\Repository\RaceRepository")
 * @Table(name="races")
 */
class Race {

    /**
     * This race's id
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * The races name
     * @var string
     * @Column(type="string", length=64)
     */
    protected $name;

    /**
     * The city in which this race lives
     * @var string
     * @Column(type="string", length=64)
     */
    protected $city;

    /**
     * The location where a new character with this race starts
     * @var LocationEntity
     * @ManyToOne(targetEntity="App\Entity\Location\LocationEntity", fetch="EXTRA_LAZY")
     * @JoinColumn(name="start_loc_id", referencedColumnName="id")
     */
    protected $startingLocation;

    /**
     * The location where the character will get if he dies
     * @var LocationEntity
     * @ManyToOne(targetEntity="App\Entity\Location\LocationEntity", fetch="EXTRA_LAZY")
     * @JoinColumn(name="death_loc_id", referencedColumnName="id")
     */
    protected $deathLocation;

    /**
     * The race's description
     * @var string
     * @Column(type="text")
     */
    protected $description;

    /**
     * The Areas which this race may visit.
     * @var Area[]
     * @ManyToMany(targetEntity="App\Entity\Location\Area", fetch="EXTRA_LAZY", cascade={"persist"})
     * @JoinTable(name="race_allowed_areas",
     *      joinColumns={@JoinColumn(name="race_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="area_id", referencedColumnName="id")}
     * )
     */
    protected $allowedAreas;

    /**
     *
     * @var boolean
     * @Column(type="boolean")
     */
    protected $allowed = true;

    public function __construct() {
        $this->members = new ArrayCollection();
        $this->allowedAreas = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getCity() {
        return $this->city;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getStartingLocation() {
        return $this->startingLocation;
    }

    public function getDeathLocation() {
        return $this->deathLocation;
    }

    public function getAllowedAreas() {
        return $this->allowedAreas;
    }

    public function getAllowed() {
        return $this->allowed;
    }

    public function setAllowed($allowed) {
        $this->allowed = $allowed;
    }

    public function setAllowedAreas(array $allowedAreas) {
        $this->allowedAreas = $allowedAreas;
    }

    public function setDeathLocation(LocationEntity $deathLocation) {
        $this->deathLocation = $deathLocation;
    }

    public function setStartingLocation(LocationEntity $startingLocation) {
        $this->startingLocation = $startingLocation;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setCity($city) {
        $this->city = $city;
    }

}
