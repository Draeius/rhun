<?php

namespace App\Entity;

use App\Util\Price;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of Wallet
 *
 * @author Matthias
 * @Entity
 * @Table(name="character_wallet")
 */
class Wallet {

    /**
     * The wallets id
     * @var int 
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * The wallets owner
     * @var Character
     * @OneToOne(targetEntity="Character", inversedBy="wallet")
     */
    protected $owner;

    /**
     * The characters gold
     * @var int
     * @Column(type="integer", nullable=false) 
     */
    protected $gold = 500;

    /**
     * The characters gold
     * @var int
     * @Column(type="integer", nullable=false) 
     */
    protected $platin = 0;

    /**
     * The characters gold
     * @var int
     * @Column(type="integer", nullable=false) 
     */
    protected $gems = 0;

    /**
     * The characters gold
     * @var int
     * @Column(type="integer", nullable=false) 
     */
    protected $bankGold = 0;

    /**
     * The characters gold
     * @var int
     * @Column(type="integer", nullable=false) 
     */
    protected $bankPlatin = 0;

    public function addGold($amount) {
        $this->gold += $amount;
        if ($this->gold < 0) {
            $this->gold = 0;
        }
    }

    public function addPlatin($amount) {
        $this->platin += $amount;
        if ($this->platin < 0) {
            $this->platin = 0;
        }
    }

    public function addGems($amount) {
        $this->gems += $amount;
        if ($this->gems < 0) {
            $this->gems = 0;
        }
    }

    /**
     * Adds the given price to the wallet.
     * @param Price $price
     */
    public function addPrice(Price $price) {
        $this->addGold($price->getGold());
        $this->addPlatin($price->getPlatin());
        $this->addGems($price->getGems());
    }

    public function subtractPrice(Price $price) {
        //invert price
        $this->addPrice($price->multiply(-1));
    }

    public function transferGoldToBank($amount) {
        if ($amount < 0) {
            return $this->transferGoldToWallet($amount * -1);
        }
        if ($amount > $this->gold) {
            $amount = $this->gold;
        }
        $this->transferGold($amount);
    }

    public function transferGoldToWallet($amount) {
        if ($amount < 0) {
            return $this->transferGoldToBank($amount * -1);
        }
        if ($amount > $this->bankGold) {
            $amount = $this->bankGold;
        }
        $this->transferGold($amount * -1);
    }

    private function transferGold($amount) {
        $this->gold -= $amount;
        $this->bankGold += $amount;
    }

    public function transferPlatinToBank($amount) {
        if ($amount < 0) {
            return $this->transferPlatinToWallet($amount * -1);
        }
        if ($amount > $this->platin) {
            $amount = $this->platin;
        }
        $this->transferPlatin($amount);
    }

    public function transferPlatinToWallet($amount) {
        if ($amount < 0) {
            return $this->transferPlatinToBank($amount * -1);
        }
        if ($amount > $this->bankPlatin) {
            $amount = $this->bankPlatin;
        }
        $this->transferPlatin($amount * -1);
    }

    private function transferPlatin($amount) {
        $this->platin -= $amount;
        $this->bankPlatin += $amount;
    }

    /**
     * Checks if there is enough money for the given price
     * @param Price $price
     */
    public function checkPrice(Price $price) {
        if($this->gold < $price->getGold()){
            return false;
        }
        if($this->platin < $price->getPlatin()){
            return false;
        }
        if($this->gems < $price->getGems()){
            return false;
        }
        return true;
    }

    public function getGold() {
        return $this->gold;
    }

    public function getPlatin() {
        return $this->platin;
    }

    public function getGems() {
        return $this->gems;
    }

    public function getBankGold() {
        return $this->bankGold;
    }

    public function getBankPlatin() {
        return $this->bankPlatin;
    }

    public function getOwner() {
        return $this->owner;
    }

    public function setOwner(Character $owner) {
        $this->owner = $owner;
    }

    public function setGold($gold) {
        $this->gold = $gold;
    }

    public function setPlatin($platin) {
        $this->platin = $platin;
    }

    public function setGems($gems) {
        $this->gems = $gems;
    }

    public function setBankGold($bankGold) {
        $this->bankGold = $bankGold;
    }

    public function setBankPlatin($bankPlatin) {
        $this->bankPlatin = $bankPlatin;
    }

}
