<?php

namespace App\Entity;

use App\Entity\Navigation;
use App\Entity\Traits\EntityColoredNameTrait;
use App\Entity\Traits\EntityIdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Represents a Location
 *
 * @author Draeius
 * @Entity
 * @HasLifecycleCallbacks
 * @Table(name="locations")
 */
class Location extends RhunEntity {

    use EntityIdTrait;
    use EntityColoredNameTrait;

    /**
     * The Area in which this location is set
     * @var Area
     * @ManyToOne(targetEntity="Area")
     * @JoinColumn(name="area_id", referencedColumnName="id")
     */
    protected $area;

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
     * If this location is for adults only
     * @var bool
     * @Column(type="boolean")
     */
    protected $adult;

    public function __construct() {
        $this->navs = new ArrayCollection();
    }

    function getDescriptionSpring() {
        return $this->descriptionSpring;
    }

    function getDescriptionSummer() {
        return $this->descriptionSummer;
    }

    function getDescriptionFall() {
        return $this->descriptionFall;
    }

    function getDescriptionWinter() {
        return $this->descriptionWinter;
    }

    function getArea(): Area {
        return $this->area;
    }

    function setArea(Area $area) {
        $this->area = $area;
    }

    function getAdult() {
        return $this->adult;
    }

    function addNav(Navigation &$nav) {
        $nav->setLocation($this); //update the nav
        $this->navs[] = $nav;
    }

    function setDescriptionSpring($descriptionSpring) {
        $this->descriptionSpring = $descriptionSpring;
    }

    function setDescriptionSummer($descriptionSummer) {
        $this->descriptionSummer = $descriptionSummer;
    }

    function setDescriptionFall($descriptionFall) {
        $this->descriptionFall = $descriptionFall;
    }

    function setDescriptionWinter($descriptionWinter) {
        $this->descriptionWinter = $descriptionWinter;
    }

    function setAdult($adult) {
        $this->adult = $adult;
    }

}
