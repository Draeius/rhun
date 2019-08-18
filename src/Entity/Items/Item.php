<?php

namespace App\Entity\Items;

use App\Entity\RhunEntity;
use App\Entity\Traits\EntityColoredNameTrait;
use App\Entity\Traits\EntityDescriptionTrait;
use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\PriceTrait;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\InheritanceType;

/**
 * An item that a Character can possess and carry around in his inventory
 *
 * @author Draeius
 * 
 * @Entity
 * @HasLifecycleCallbacks
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({
 *      "armor" = "Armor",
 *      "armTempl" = "ArmorTemplate",
 *      "healthPot" = "HealthPotion",
 *      "savBox" = "SavingsBox",
 *      "titleStorg" = "TitleStorage",
 *      "weapon" = "Weapon",
 *      "wpnTempl" = "WeaponTemplate"
 * })
 */
abstract class Item extends RhunEntity {

    use EntityColoredNameTrait;
    use EntityDescriptionTrait;
    use EntityIdTrait;
    use PriceTrait;

    /**
     *
     * @var bool If this item is created by a player or a default item
     * @Column(type="boolean")
     */
    protected $madeByPlayer = false;

    /**
     *
     * @Column(type="string", length=128, nullable=true) 
     */
    protected $icon;

    public function __toString() {
        return $this->getName();
    }

    public function getBuyPrice() {
        return $this->buyPrice;
    }

    public function getSellPrice() {
        return $this->sellPrice;
    }

    public function getMadeByPlayer() {
        return $this->madeByPlayer;
    }

    public function getIcon() {
        return $this->icon;
    }

    public function setIcon($icon) {
        $this->icon = $icon;
    }

    public function setBuyPrice($buyPrice) {
        $this->buyPrice = $buyPrice;
    }

    public function setSellPrice($sellPrice) {
        $this->sellPrice = $sellPrice;
    }

    public function setMadeByPlayer($madeByPlayer) {
        $this->madeByPlayer = $madeByPlayer;
    }

    function getDisplayTemplate() {
        return 'parts/item.html.twig';
    }

}
