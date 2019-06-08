<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\Table;

/**
 * An item that a Character can possess and carry around in his inventory
 *
 * @author Draeius
 * 
 * @Entity
 * @Table(name="items")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({
 *      "weapon" = "Weapon",
 *      "weapon_templ" = "WeaponTemplate",
 *      "armor" = "Armor",
 *      "armor_templ" = "ArmorTemplate",
 *      "potion" = "HealthPotion",
 *      "item" = "Item",
 *      "sav_box" = "HouseBundle\Entity\SavingsBox",
 *      "title_storage" = "HouseBundle\Entity\TitleStorage"
 * })
 */
class Item {

    /**
     * 
     * @var int The id of this item
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     *
     * @var string This item's name 
     * @Column(type="string", length=128, nullable=false)
     */
    protected $name;

    /**
     *
     * @var string This item's name 
     * @Column(type="string", length=128, nullable=false)
     */
    protected $nameID;

    /**
     *
     * @var integer The price at which this item can be bought
     * @Column(type="integer", nullable=false)
     */
    protected $buyPrice;

    /**
     *
     * @var int The price at which this item can be sold
     * @Column(type="integer", nullable=false) 
     */
    protected $sellPrice;

    /**
     *
     * @var string A description of this item
     * @Column(type="text") 
     */
    protected $description;

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

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getBuyPrice() {
        return $this->buyPrice;
    }

    public function getSellPrice() {
        return $this->sellPrice;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getMadeByPlayer() {
        return $this->madeByPlayer;
    }

    public function getIcon() {
        return $this->icon;
    }

    public function getNameID() {
        return $this->nameID;
    }

    public function setNameID($nameID) {
        $this->nameID = $nameID;
    }

    public function setIcon($icon) {
        $this->icon = $icon;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setBuyPrice($buyPrice) {
        $this->buyPrice = $buyPrice;
    }

    public function setSellPrice($sellPrice) {
        $this->sellPrice = $sellPrice;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setMadeByPlayer($madeByPlayer) {
        $this->madeByPlayer = $madeByPlayer;
    }

    function getDisplayTemplate() {
        return 'parts/item.html.twig';
    }

}
