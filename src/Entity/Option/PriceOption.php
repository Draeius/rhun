<?php

namespace App\Entity\Option;

use App\Entity\Character;
use App\Entity\Option\Option;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * 
 * @Entity
 * @Table(name="options_price")
 */
class PriceOption extends Option {

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $priceGold;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $pricePlatin;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $priceGems;

    public function execute(EntityManagerInterface $eManager, Character $character) {
        $character->getWallet()->addPrice($this->price);

        $eManager->persist($character->getWallet());
        $eManager->flush();
    }

    function getPriceGold() {
        return $this->priceGold;
    }

    function getPricePlatin() {
        return $this->pricePlatin;
    }

    function getPriceGems() {
        return $this->priceGems;
    }

    function setPriceGold($priceGold) {
        $this->priceGold = $priceGold;
    }

    function setPricePlatin($pricePlatin) {
        $this->pricePlatin = $pricePlatin;
    }

    function setPriceGems($priceGems) {
        $this->priceGems = $priceGems;
    }

}
