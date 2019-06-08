<?php

namespace App\Entity\Location;

use App\Util\Config\Config;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use App\Entity\Area;
use App\Entity\Navigation;

/**
 * Represents a Location
 *
 * @author Draeius
 * @Entity
 * @Table(name="location")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({
 *      "base" = "LocationEntity",
 *      "post" = "PostableLocationEntity",
 *      "shop" = "ShopLocationEntity",
 *      "house" = "HousingLocationEntity", "PostableLocationEntity", 
 *      "ccenter" = "CityCenterLocationEntity", 
 *      "craft" = "CraftingLocationEntity",
 *      "fight" = "FightingLocationEntity",
 *      "respawn" = "RespawnLocationEntity",
 *      "bank" = "BankLocationEntity",
 *      "hallway" = "HallwayLocationEntity",
 *      "bedroom" = "BedroomLocationEntity",
 *      "kitchen" = "KitchenLocationEntity",
 *      "workroom" = "WorkroomLocationEntity",
 *      "plain_room" = "RoomLocationEntity",
 *      "school" = "SchoolLocationEntity",
 *      "library" = "LibraryLocationEntity",
 *      "bookWriting" = "BookWritingLocationEntity",
 *      "gemShop" = "GemShopLocationEntity",
 *      "workplace" = "JobLocationEntity",
 *      "pvp" = "PvpLocationEntity",
 *      "text" = "TextLocationEntity",
 *      "stock" = "StockExchangeLocationEntity",
 *      "graveyard" = "GraveyardLocationEntity",
 *      "stage" = "StageLocationEntity",
 *      "postOffice" = "PostOfficeLocationEntity",
 *      "bulletin" = "BulletinLocationEntity",
 *      "raceOv" = "RaceOverviewLocationEntity",
 *      "guildList" = "GuildListLocationEntity",
 *      "guildAdm" = "GuildAdministrationLocationEntity",
 *      "dungeon" = "DungeonLocationEntity", "FightingLocationEntity"
 * })
 */
abstract class LocationEntity {

    /**
     * This locations id
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * The locations title
     * @var string
     * @Column(type="string", length=128, nullable=false)
     */
    protected $title;

    /**
     * The locations description in spring
     * @var string
     * @Column(type="text", nullable=false)
     */
    protected $descriptionSpring;

    /**
     * The locations description in summer
     * @var string
     * @Column(type="text", nullable=true)
     */
    protected $descriptionSummer;

    /**
     * The locations description in fall
     * @var string
     * @Column(type="text", nullable=true)
     */
    protected $descriptionFall;

    /**
     * The locations description in winter
     * @var string
     * @Column(type="text", nullable=true)
     */
    protected $descriptionWinter;

    /**
     * The Area in which this location is set
     * @var Area
     * @ManyToOne(targetEntity="Area")
     * @JoinColumn(name="area_id", referencedColumnName="id")
     */
    protected $area;

    /**
     * If this location is for adults only
     * @var bool
     * @Column(type="boolean")
     */
    protected $adult;

    /**
     * All navs displayed in this location
     * @var Navigation[]
     * @OneToMany(targetEntity="Navigation", mappedBy="location", cascade={"remove"}, fetch="EXTRA_LAZY")
     */
    protected $navs;

    /**
     * All navs that lead to this location
     * @var Navigation[]
     * @OneToMany(targetEntity="Navigation", mappedBy="targetLocation", cascade={"remove"}, fetch="EXTRA_LAZY")
     */
    protected $incomingNavs;

    public function __construct() {
        $this->navs = new ArrayCollection();
    }

    public abstract function getTemplate();

    public abstract function getLocationInstance(EntityManager $manager, string $uuid, Config $config);

    public function hasNav(int $target): bool {
        foreach ($this->navs as $nav) {
            if ($nav->getTargetLocation() == $target) {
                return true;
            }
        }
        return false;
    }

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescriptionSpring() {
        return $this->descriptionSpring;
    }

    public function getDescriptionSummer() {
        return $this->descriptionSummer;
    }

    public function getDescriptionFall() {
        return $this->descriptionFall;
    }

    public function getDescriptionWinter() {
        return $this->descriptionWinter;
    }

    public function getArea() {
        return $this->area;
    }

    public function getNavs() {
        return $this->navs;
    }

    public function getAdult() {
        return $this->adult;
    }

    public function setAdult($adult) {
        $this->adult = $adult;
    }

    public function addNav(Navigation $nav) {
        $nav->setLocation($this); //update the nav
        $this->navs[] = $nav;
    }

    public function setArea(Area $area) {
        $this->area = $area;
    }

    public function setNavs(array $navs) {
        $this->navs = $navs;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setDescriptionSpring($descriptionSpring) {
        $this->descriptionSpring = $descriptionSpring;
    }

    public function setDescriptionSummer($descriptionSummer) {
        $this->descriptionSummer = $descriptionSummer;
    }

    public function setDescriptionFall($descriptionFall) {
        $this->descriptionFall = $descriptionFall;
    }

    public function setDescriptionWinter($descriptionWinter) {
        $this->descriptionWinter = $descriptionWinter;
    }

}
