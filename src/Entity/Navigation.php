<?php

namespace App\Entity;

use App\Entity\Traits\EntityColoredNameTrait;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * This class describes a usable path from one Location to another
 *
 * @author Draeius
 * @Entity
 * @Table(name="navigations")
 */
class Navigation extends LocationBasedEntity {

    use EntityColoredNameTrait;
//    use EntityIdTrait;

    /**
     * The target of this nav
     * @var LocationEntity
     * @ManyToOne(targetEntity="Location", fetch="EXTRA_LAZY")
     * @JoinColumn(name="target_location_id", referencedColumnName="id", nullable=true)
     */
    protected $targetLocation;

    /**
     * If this is set, the target location is ignored an instead this href is used.
     * @var string
     * @Column(type="string", length=256)
     */
    protected $href = '';

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

    public function getTargetLocation() {
        return $this->targetLocation;
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

    public function setTargetLocation(Location $targetLocation) {
        $this->targetLocation = $targetLocation;
    }

    public function setHref($href) {
        $this->href = $href;
    }

    public function setPopup($popup) {
        $this->popup = $popup;
    }

}
