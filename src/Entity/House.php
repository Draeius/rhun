<?php

namespace App\Entity;

use App\Entity\Character;
use App\Entity\HouseLocation;
use App\Entity\Location;
use App\Entity\RhunEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * A house, buildable by a character
 *
 * @author Draeius
 * @Entity()
 * @Table(name="houses")
 */
class House extends RhunEntity {

    /**
     * Where this house is standing
     * @var LocationEntity
     * @ManyToOne(targetEntity="Location")
     * @JoinColumn(name="location_id", referencedColumnName="id")
     */
    protected $location;

    /**
     * The title of this house
     * @var string
     * @Column(type="string", length=128)
     */
    protected $title;

    /**
     * How many rooms the house may have
     * @var int
     * @Column(type="integer")
     */
    protected $maxRooms = 3;

    /**
     * The rooms in this house
     * @var Room[]
     * @OneToMany(targetEntity="HouseLocation", mappedBy="house", cascade={"remove", "persist"}, fetch="EXTRA_LAZY")
     */
    protected $rooms;

    /**
     * The owner of this house
     * @var Character
     * @ManyToOne(targetEntity="Character")
     * @JoinColumn(name="owner_id", referencedColumnName="id")
     */
    protected $owner;

    /**
     * Player that may enter this house
     * @var Character[]
     * @ManyToMany(targetEntity="Character", fetch="EXTRA_LAZY")
     * @JoinTable(name="house_keys",
     *          joinColumns={@JoinColumn(name="house_id", referencedColumnName="id")},
     *          inverseJoinColumns={@JoinColumn(name="character_id", referencedColumnName="id")}
     *          )
     */
    protected $inhabitants;

    /**
     * The description of this house
     * @var string
     * @Column(type="text")
     */
    protected $description;

    /**
     * The description of this house
     * @var string
     * @Column(type="text")
     */
    protected $script;

    /**
     * The avatar (an image) of this house
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $avatar;

    /**
     * 
     * @var HouseFunctionsEntity
     * @OneToOne(targetEntity="HouseFunctionsEntity", fetch="EXTRA_LAZY", cascade={"remove"})
     * @JoinColumn(name="functions_id", referencedColumnName="id")
     */
    protected $houseFunctions;

    /**
     * 
     * @var bool
     * @Column(type="boolean")
     */
    protected $showOwner;

    public function __construct() {
        $this->rooms = new ArrayCollection();
        $this->inhabitants = new ArrayCollection();
    }

    public function addInhabitant(Character $toAdd) {
        foreach ($this->inhabitants as $char) {
            if ($char->getId() == $toAdd->getId()) {
                return;
            }
        }
        $this->inhabitants[] = $toAdd;
    }

    public function removeInhabitant(Character $remove) {
        $this->inhabitants->removeElement($remove);
    }

    public function isInhabitant(Character $character) {
        foreach ($this->inhabitants as $char) {
            if ($char->getId() == $character->getId()) {
                return true;
            }
        }
        return false;
    }

    public function getMaxRooms() {
        return $this->maxRooms;
    }

    public function getRooms() {
        return $this->rooms;
    }

    public function getOwner() {
        return $this->owner;
    }

    public function getInhabitants() {
        return $this->inhabitants;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getAvatar() {
        return $this->avatar;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getScript() {
        return $this->script;
    }

    public function getShowOwner() {
        return $this->showOwner;
    }

    public function setShowOwner($showOwner) {
        $this->showOwner = $showOwner;
    }

    public function setScript($script) {
        $this->script = $script;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setLocation(LocationEntity $location) {
        $this->location = $location;
    }

    public function setMaxRooms($maxRooms) {
        $this->maxRooms = $maxRooms;
    }

    public function setRooms(array $rooms) {
        $this->rooms = $rooms;
    }

    public function addRoom(Room $room) {
        $this->rooms[] = $room;
        $room->setHouse($this);
    }

    public function setOwner(Character $owner) {
        $this->owner = $owner;
    }

    public function setInhabitants(array $inhabitants) {
        $this->inhabitants = $inhabitants;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setAvatar($avatar) {
        $this->avatar = $avatar;
    }

    public function getHouseFunctions() {
        return $this->houseFunctions;
    }

    public function setHouseFunctions(HouseFunctionsEntity $houseFunctions) {
        $this->houseFunctions = $houseFunctions;
    }

}
