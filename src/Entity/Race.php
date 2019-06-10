<?php

namespace App\Entity;

use App\Entity\Area;
use App\Entity\Traits\EntityColoredNameTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotBlank;



/**
 * Represents a race
 *
 * @author Draeius
 * @Entity(repositoryClass="App\Repository\RaceRepository")
 * @Table(name="races")
 * @UniqueEntity("coloredName")
 */
class Race extends LocationBasedEntity {

    use EntityColoredNameTrait;
    
    /**
     * The city in which this race lives
     * @var string
     * @NotBlank
     * @Column(type="string", length=64)
     */
    protected $city;

    /**
     * The location where the character will get if he dies
     * @var Location
     * @ManyToOne(targetEntity="Location", fetch="EXTRA_LAZY")
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
     * @ManyToMany(targetEntity="Area", fetch="EXTRA_LAZY", cascade={"persist"})
     * @JoinTable(name="race_allowed_areas",
     *      joinColumns={@JoinColumn(name="race_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="area_id", referencedColumnName="id")}
     * )
     */
    protected $allowedAreas;

    /**
     *
     * @var boolean
     * @Column(type="boolean", nullable=false, options={"default":0})
     */
    protected $allowed = true;

    public function __construct() {
        $this->members = new ArrayCollection();
        $this->allowedAreas = new ArrayCollection();
    }

    public function getCity() {
        return $this->city;
    }

    public function getDescription() {
        return $this->description;
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

    public function setDeathLocation(Location $deathLocation) {
        $this->deathLocation = $deathLocation;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setCity($city) {
        $this->city = $city;
    }

}
