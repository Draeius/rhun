<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * This class describes a usable path from one Location to another
 *
 * @author Draeius
 * @Entity
 * @Table(name="navigation")
 */
class Navigation {

    /**
     * This navigations id
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * The label of this nav that is displayed to the user
     * @var string
     * @Column(type="string", length=128)
     */
    protected $label;

    /**
     * The location where this nav is shown
     * @var LocationEntity
     * @ManyToOne(targetEntity="LocationEntity", inversedBy="navs", fetch="EXTRA_LAZY")
     * @JoinColumn(name="location_id", referencedColumnName="id")
     */
    protected $location;

    /**
     * The target of this nav
     * @var LocationEntity
     * @ManyToOne(targetEntity="LocationEntity", inversedBy="incomingNavs", fetch="EXTRA_LAZY")
     * @JoinColumn(name="target_location_id", referencedColumnName="id", nullable=true)
     */
    protected $targetLocation;

    /**
     * Parameters that are sent with this nav
     * @var string
     * @Column(type="text", nullable=true)
     */
    protected $params='';

    /**
     * If this is set, the target location is ignored an instead this href is used.
     * @var string
     * @Column(type="string", length=256)
     */
    protected $href='';

    /**
     * If the href is set, this defines if the link is opened in a popup window.
     * @var bool
     * @Column(type="boolean")
     */
    protected $popup = false;

    /**
     * The index at which this nav is displayed
     * @var int
     * @Column(type="integer")
     */
    protected $navbarIndex = 0;

    public function decodeParams(){
        return json_decode($this->params, true);
    }
    
    public function getId() {
        return $this->id;
    }

    public function getLabel() {
        return $this->label;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getTargetLocation() {
        return $this->targetLocation;
    }

    public function getParams() {
        return $this->params;
    }

    public function getHref() {
        return $this->href;
    }

    public function getPopup() {
        return $this->popup;
    }

    public function getNavbarIndex() {
        return $this->navbarIndex;
    }

    public function setNavbarIndex($navbarIndex) {
        $this->navbarIndex = $navbarIndex;
    }

    public function setLabel($label) {
        $this->label = $label;
    }

    public function setLocation(LocationEntity $location) {
        $this->location = $location;
    }

    public function setTargetLocation(LocationEntity $targetLocation) {
        $this->targetLocation = $targetLocation;
    }

    public function setParams($params) {
        $this->params = $params;
    }

    public function setHref($href) {
        $this->href = $href;
    }

    public function setPopup($popup) {
        $this->popup = $popup;
    }

}
