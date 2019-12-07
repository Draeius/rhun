<?php

namespace App\Entity\Option;

use App\Entity\Character;
use App\Service\CharacterService;
use App\Util\Price;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="options_health")
 */
class HealthOption extends Option {

    /**
     *
     * @var float
     * @Column(type="float")
     */
    protected $amount;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $isPercentage;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $priceRequired;

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
        $amount = $this->amount;

        if ($this->isPercentage) {
            $amount = $character->getMaxHPWithBuff() * ($amount / 100);
        }

        $price = new Price($this->priceGold, $this->pricePlatin, $this->priceGems);
        if (!$this->priceRequired || $character->getWallet()->checkPrice($price)) {
            $character->addHP($amount);
            $character->getWallet()->addPrice($price);

            if ($character->getCurrentHP() == 0) {
                CharacterService::handleDeath($character);
            }

            $this->getManager()->persist($character);
            $this->getManager()->flush();
        }
    }

    function getAmount() {
        return $this->amount;
    }

    function getIsPercentage() {
        return $this->isPercentage;
    }

    function getPriceRequired() {
        return $this->priceRequired;
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

    function setAmount($amount) {
        $this->amount = $amount;
    }

    function setIsPercentage($isPercentage) {
        $this->isPercentage = $isPercentage;
    }

    function setPriceRequired($priceRequired) {
        $this->priceRequired = $priceRequired;
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
