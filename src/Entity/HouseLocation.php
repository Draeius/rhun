<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of HouseLocation
 *
 * @author Draeius
 * @Entity
 * @Table(name="house_locations")
 */
class HouseLocation extends LocationBasedEntity {

    /**
     *
     * @var bool
     * @Column(type="boolean", nullable=false)
     */
    protected $entrance;

    /**
     *
     * @var House
     * @ManyToOne(targetEntity="App\Entity\House", inversedBy="rooms")
     * @JoinColumn(name="house_id", referencedColumnName="id")
     */
    protected $house;

}
