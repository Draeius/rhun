<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

/**
 * Armor that the player can use
 *
 * @author Draeius
 * @Entity(repositoryClass="App\Repository\ArmorTemplateRepository")
 */
class ArmorTemplate extends Item {

    /**
     *
     * @var int The armor's defense
     * @Column(type="integer", nullable=false)
     */
    protected $armorType;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $attribute;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $secondAttribute;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $minAttribute;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $minSecondAttr;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $staminaDrain;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $level;

    public function getId() {
        return $this->id;
    }

    public function getArmorType() {
        return $this->armorType;
    }

    public function getAttribute() {
        return $this->attribute;
    }

    public function getMinAttribute() {
        return $this->minAttribute;
    }

    public function getStaminaDrain() {
        return $this->staminaDrain;
    }

    public function getLevel() {
        return $this->level;
    }

    public function getSecondAttribute() {
        return $this->secondAttribute;
    }

    public function getMinSecondAttr() {
        return $this->minSecondAttr;
    }

    public function setSecondAttribute($secondAttribute) {
        $this->secondAttribute = $secondAttribute;
    }

    public function setMinSecondAttr($minSecondAttr) {
        $this->minSecondAttr = $minSecondAttr;
    }

    public function setLevel($level) {
        $this->level = $level;
    }

    public function setArmorType($armorType) {
        $this->armorType = $armorType;
    }

    public function setAttribute($attribute) {
        $this->attribute = $attribute;
    }

    public function setMinAttribute($minAttribute) {
        $this->minAttribute = $minAttribute;
    }

    public function setStaminaDrain($staminaDrain) {
        $this->staminaDrain = $staminaDrain;
    }

    function getDisplayTemplate() {
        return 'parts/armor.html.twig';
    }

}
