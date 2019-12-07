<?php

namespace App\Entity\Option;

use App\Entity\Character;
use App\Service\CharacterService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="options_death")
 */
class DeathOption extends Option {

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    private $looseWallet = true;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    private $relocate = false;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    private $expLoss = true;

    public function execute(EntityManagerInterface $eManager, Character $character) {
        CharacterService::handleDeath($eManager(), $character, $this->looseWallet, $this->relocate, $this->expLoss);
    }

    function getLooseWallet() {
        return $this->looseWallet;
    }

    function getRelocate() {
        return $this->relocate;
    }

    function getExpLoss() {
        return $this->expLoss;
    }

    function setLooseWallet($looseWallet) {
        $this->looseWallet = $looseWallet;
    }

    function setRelocate($relocate) {
        $this->relocate = $relocate;
    }

    function setExpLoss($expLoss) {
        $this->expLoss = $expLoss;
    }

}
