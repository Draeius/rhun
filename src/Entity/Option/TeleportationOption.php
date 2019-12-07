<?php

namespace App\Entity\Option;

use App\Entity\Character;
use App\Entity\Location;
use App\Entity\Option\Option;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * 
 * @Entity
 * @Table(name="options_port")
 */
class TeleportationOption extends Option {

    /**
     * 
     * @var Location 
     * @ManyToOne(targetEntity="App\Entity\Location")
     * @JoinColumn(name="location_id", referencedColumnName="id")
     */
    private $targetLocation;

    public function execute(EntityManagerInterface $eManager, Character $character) {
        $character->setLocation($this->targetLocation);

        $eManager->persist($character);
        $eManager->flush();
    }

}
