<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use App\Entity\Location\LocationEntity;

/**
 * Specifies which monster can spawn in which area at what rate
 *
 * @author Draeius
 * @Entity(repositoryClass="App\Repository\MonsterOccuranceRepository")
 * @Table(name="monster_occurances")
 */
class MonsterOccurance {

    /**
     * The Monster Occurances id
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue 
     */
    protected $id;

    /**
     * The enemy this class refers to
     * @var Enemy
     * @ManyToOne(targetEntity="App\Entity\Enemy", inversedBy="occurances")
     * @JoinColumn(name="monster_id", referencedColumnName="id")
     */
    protected $monster;

    /**
     * The area at which the monster can spawn
     * @var LocationEntity
     * @ManyToOne(targetEntity="App\Entity\Location\FightingLocationEntity", inversedBy="occurances")
     * @JoinColumn(name="location_id", referencedColumnName="id")
     */
    protected $location;

    /**
     * The rate at which the specified monster will spawn.
     * @var int 
     * @Column(type="integer")
     */
    protected $rate;

    public function getId() {
        return $this->id;
    }

    public function getMonster() {
        return $this->monster;
    }

    public function getRate() {
        return $this->rate;
    }

    public function getLocation() {
        return $this->location;
    }

    public function setLocation(LocationEntity $location) {
        $this->location = $location;
    }

    public function setMonster(Enemy $monster) {
        $this->monster = $monster;
    }

    public function setRate($rate) {
        $this->rate = $rate;
    }

}
