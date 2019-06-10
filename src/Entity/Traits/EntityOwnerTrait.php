<?php

namespace App\Entity\Traits;

use App\Entity\Character;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Description of EntityOwnerTrait
 *
 * @author Draeius
 */
trait EntityOwnerTrait {

    /**
     * The owner of this biography
     * @var Character 
     * @ManyToOne(targetEntity="Character")
     * @JoinColumn(name="owner_id", referencedColumnName="id")
     */
    protected $owner;

    public function getOwner(): Character {
        return $this->owner;
    }

    public function setOwner(Character $character): void {
        $this->owner = $character;
    }

    public function isOwner(Character $test) {
        return $test->getId() == $this->owner->getId();
    }

}
