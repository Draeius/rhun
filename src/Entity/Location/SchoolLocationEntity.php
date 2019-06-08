<?php

namespace App\Entity\Location;

use AppBundle\Util\Config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use App\Entity\Location\PostableLocationEntity;
use NavigationBundle\Location\SchoolLocation;


/**
 * Description of RoomLocationEntity
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_school")
 */
class SchoolLocationEntity extends PostableLocationEntity {

    /**
     *
     * @var boolean
     * @Column(type="boolean")
     */
    protected $intelligence = false;

    /**
     *
     * @var boolean
     * @Column(type="boolean")
     */
    protected $agility = false;

    /**
     *
     * @var boolean
     * @Column(type="boolean")
     */
    protected $strength = false;

    /**
     *
     * @var boolean
     * @Column(type="boolean")
     */
    protected $charme = false;

    public function getTemplate() {
        return 'locations/schoolLocation';
    }

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new SchoolLocation($manager, $uuid, $this, $config);
    }

    public function getIntelligence() {
        return $this->intelligence;
    }

    public function getAgility() {
        return $this->agility;
    }

    public function getStrength() {
        return $this->strength;
    }

    public function getCharme() {
        return $this->charme;
    }

    public function setIntelligence($intelligence) {
        $this->intelligence = $intelligence;
    }

    public function setAgility($agility) {
        $this->agility = $agility;
    }

    public function setStrength($strength) {
        $this->strength = $strength;
    }

    public function setCharme($charme) {
        $this->charme = $charme;
    }

}
