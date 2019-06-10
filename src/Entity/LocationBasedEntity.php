<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\MappedSuperclass;

/**
 * Description of LocationBasedEntity
 *
 * @author Draeius
 * @MappedSuperclass
 */
abstract class LocationBasedEntity extends RhunEntity {

    /**
     * 
     * @var Location 
     * @ManyToOne(targetEntity="App\Entity\Location", fetch="EXTRA_LAZY")
     * @JoinColumn(name="location_id", referencedColumnName="id")
     */
    protected $location;

    function getLocation(): Location {
        return $this->location;
    }

    function setLocation(Location $location) {
        $this->location = $location;
    }

}
