<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Specifies which monster can spawn in which area at what rate
 *
 * @author Draeius
 * @Entity(repositoryClass="App\Repository\MonsterOccuranceRepository")
 * @Table(name="monster_occurances")
 */
class MonsterOccurance extends LocationBasedEntity {

    /**
     * The enemy this class refers to
     * @var Enemy
     * @ManyToOne(targetEntity="Monster", inversedBy="occurances")
     * @JoinColumn(name="monster_id", referencedColumnName="id")
     */
    protected $monster;

    /**
     * The rate at which the specified monster will spawn.
     * @var int 
     * @Column(type="integer")
     */
    protected $rate;

    public function getMonster() {
        return $this->monster;
    }

    public function getRate() {
        return $this->rate;
    }

    public function setMonster(Enemy $monster) {
        $this->monster = $monster;
    }

    public function setRate($rate) {
        $this->rate = $rate;
    }

}
