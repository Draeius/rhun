<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use App\Entity\Location\LocationEntity;

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
abstract class SpecialEntity {

    /**
     * The specials id
     * @var int 
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     *
     * @var LocationEntity
     * @ManyToOne(targetEntity="App\Entity\Location\FightingLocationEntity", inversedBy="specials")
     * @JoinColumn(name="location_id", referencedColumnName="id")
     */
    protected $location;

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

    public function getId() {
        return $this->id;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getProbability() {
        return $this->probability;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setLocation(LocationEntity $location) {
        $this->location = $location;
    }

    public function setProbability($probability) {
        $this->probability = $probability;
    }

    /**
     * @return Special
     */
    public abstract function getSpecialClass();    
}
