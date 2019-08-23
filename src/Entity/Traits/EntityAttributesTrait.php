<?php

namespace App\Entity\Traits;

use App\Entity\Attribute;
use Doctrine\ORM\Mapping\Column;

/**
 * Description of AttributesTrait
 *
 * @author Draeius
 */
trait EntityAttributesTrait {

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $strength = 10;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $dexterity = 10;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $constitution = 10;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $intelligence = 10;

    /**
     *
     * @var int
     * @Column(name="precision_attr", type="integer")
     */
    protected $wisdom = 10;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $willpower = 10;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $reputation = 0;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $skillPoints = 20;

    public function getAttribute(int $attribute): int {
        switch ($attribute) {
            case Attribute::STRENGTH:
                return $this->getStrength();
            case Attribute::DEXTERITY:
                return $this->getDexterousness();
            case Attribute::CONSTITUTION:
                return $this->getConstitution();
            case Attribute::INTELLIGENCE:
                return $this->getIntelligence();
            case Attribute::WISDOM:
                return $this->getWisdom();
            case Attribute::WILLPOWER:
                return $this->getWillpower();
        }
        return 0;
    }

    public function setAttribute(int $attribute, int $value) {
        if ($value > 20) {
            $value = 20;
        }
        if ($value < 0) {
            $value = 0;
        }
        $this->set($attribute, $value);
    }

    public function addToAttribute(int $attribute, int $value): int {
        $newValue = $this->getAttribute($attribute) + $value;
        $this->setAttribute($newValue);
        return $newValue - $this->getAttribute($attribute);
    }

    public function addReputation($amount) {
        $this->reputation += $amount;
    }

    private function set(int $attribute, int $value) {
        switch ($attribute) {
            case Attribute::STRENGTH:
                $this->strength = $value;
                break;
            case Attribute::DEXTERITY:
                $this->dexterity = $value;
                break;
            case Attribute::CONSTITUTION:
                $this->constitution = $value;
                break;
            case Attribute::INTELLIGENCE:
                $this->intelligence = $value;
                break;
            case Attribute::WISDOM:
                $this->precision = $value;
                break;
            case Attribute::WILLPOWER:
                $this->agility = $value;
                break;
        }
    }

    function getStrength() {
        return $this->strength;
    }

    function getDexterity() {
        return $this->dexterity;
    }

    function getConstitution() {
        return $this->constitution;
    }

    function getIntelligence() {
        return $this->intelligence;
    }

    function getWisdom() {
        return $this->wisdom;
    }

    function getWillpower() {
        return $this->willpower;
    }

    function getReputation() {
        return $this->reputation;
    }

    function getSkillPoints() {
        return $this->skillPoints;
    }

    function setStrength($strength) {
        $this->strength = $strength;
    }

    function setDexterity($dexterity) {
        $this->dexterity = $dexterity;
    }

    function setConstitution($constitution) {
        $this->constitution = $constitution;
    }

    function setIntelligence($intelligence) {
        $this->intelligence = $intelligence;
    }

    function setWisdom($wisdom) {
        $this->wisdom = $wisdom;
    }

    function setWillpower($willpower) {
        $this->willpower = $willpower;
    }

    function setReputation($reputation) {
        $this->reputation = $reputation;
    }

    function setSkillPoints($skillPoints) {
        $this->skillPoints = $skillPoints;
    }

}
