<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * Describes an Area. An Area is a collection of Locations
 *
 * @author Draeius
 * @Entity
 * @Table(name="areas")
 */
class Area {

    /**
     * This area's id
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * The Areas name
     * @var string
     * @Column(type="string", length=128)
     */
    protected $name;

    /**
     * The city this area belongs to
     * @var string
     * @Column(type="string", length=64)
     */
    protected $city;

    /**
     * A description of this area
     * @var string
     * @Column(type="text", nullable=true)
     */
    protected $description;

    /**
     *
     * @var boolean
     * @Column(type="boolean")
     */
    protected $deadAllowed;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getCity() {
        return $this->city;
    }

    public function getDeadAllowed() {
        return $this->deadAllowed;
    }

    public function setDeadAllowed($deadAllowed) {
        $this->deadAllowed = $deadAllowed;
    }

    public function setCity($city) {
        $this->city = $city;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

}
