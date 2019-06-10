<?php

namespace App\Entity;

use App\Entity\Traits\EntityOwnerTrait;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of ColoredName
 *
 * @author Draeius
 * @Entity
 * @Table(name="character_names")
 */
class ColoredName extends RhunEntity {

    use EntityOwnerTrait;

    /**
     *
     * @var Character
     * @ManyToOne(targetEntity="Character", inversedBy="coloredNames")
     */
    protected $owner;

    /**
     * @Column(type="string", length=64) 
     */
    protected $name;

    /**
     * @var boolean
     * @Column(type="boolean", nullable=false)
     */
    protected $isActivated = true;

    public function getName() {
        return $this->name;
    }

    public function getIsActivated() {
        return $this->isActivated;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setIsActivated($isActivated) {
        $this->isActivated = $isActivated;
    }

}
