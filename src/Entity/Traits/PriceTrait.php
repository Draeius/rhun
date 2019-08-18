<?php

namespace App\Entity\Traits;

use App\Util\Price;
use Doctrine\ORM\Mapping\Column;

/**
 * Fügt dem Entity einen Preis hinzu, sodass man es kaufen und verkaufen kann.
 *
 * @author Draeius
 */
trait PriceTrait {

    /**
     *
     * @var int The price at which this item can be sold
     * @Column(type="integer", nullable=false) 
     */
    private $priceGold;

    /**
     *
     * @var int The price at which this item can be sold
     * @Column(type="integer", nullable=false) 
     */
    private $pricePlatin;

    /**
     *
     * @var int The price at which this item can be sold
     * @Column(type="integer", nullable=false) 
     */
    private $priceGems;

    public function getPrice(bool $buy = true): Price {
        $price = new Price($this->priceGold, $this->pricePlatin, $this->priceGems);
        if (!$buy) {
            return $price->multiply(0.8);
        }
        return $price;
    }

    public function setPrice(Price $price) {
        $this->setPriceGold($price->getGold());
        $this->setPricePlatin($price->getPlatin());
        $this->setPriceGems($price->getGems());
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
