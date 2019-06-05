<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;

/**
 * Description of ColoredName
 *
 * @author Draeius
 * @Entity
 * @Table(name="character_names")
 */
class ColoredName {

    /**
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     *
     * @var Character
     * @ManyToOne(targetEntity="Character", inversedBy="coloredNames")
     * @JoinColumn(name="owner_id", referencedColumnName="id")
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

    public function getId() {
        return $this->id;
    }

    public function getOwner(): Character {
        return $this->owner;
    }

    public function getName() {
        return $this->name;
    }

    public function getIsActivated() {
        return $this->isActivated;
    }

    public function setOwner(Character $owner) {
        $this->owner = $owner;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setIsActivated($isActivated) {
        $this->isActivated = $isActivated;
    }

}
