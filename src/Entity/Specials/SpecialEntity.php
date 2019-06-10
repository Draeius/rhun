<?php

namespace App\Entity\Specials;

use App\Entity\LocationBasedEntity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of Special
 *
 * @author Draeius
 * @Entity
 * @Table(name="specials")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"special" = "SpecialEntity", "chest" = "ChestSpecialEntity"})
 */
abstract class SpecialEntity extends LocationBasedEntity {

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $probability;

    /**
     *
     * @var string
     * @Column(type="text")
     */
    protected $description;

    public function getProbability() {
        return $this->probability;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setProbability($probability) {
        $this->probability = $probability;
    }

    /**
     * @return Special
     */
    public abstract function getSpecialClass();
}
