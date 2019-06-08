<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of ChestSpecial
 *
 * @author Draeius
 * @Entity
 * @Table(name="special_chest")
 */
class ChestSpecialEntity extends SpecialEntity {

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $goldAmount = 0;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $platinAmount = 0;

    public function getGoldAmount() {
        return $this->goldAmount;
    }

    public function addGold($amount) {
        $this->goldAmount += $amount;
        if ($this->goldAmount < 0) {
            $this->goldAmount = 0;
        }
    }

    public function getPlatinAmount() {
        return $this->platinAmount;
    }

    public function addPlatin($amount) {
        $this->platinAmount += $amount;
        if ($this->platinAmount < 0) {
            $this->platindAmount = 0;
        }
    }

    public function setGoldAmount($goldAmount) {
        $this->goldAmount = $goldAmount;
    }

    public function setPlatinAmount($platinAmount) {
        $this->platinAmount = $platinAmount;
    }

    public function getSpecialClass() {
        return new ChestSpecial(new TemplatePart('specials/chestSpecial'), $this);
    }

}
