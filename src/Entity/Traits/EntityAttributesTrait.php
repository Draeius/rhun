<?php

namespace App\Entity\Traits;

use App\Entity\Attribute;
use Doctrine\ORM\Mapping\Column;

/**
 * Description of AttributesTrait
 *
 * @author Matthias
 */
trait EntityAttributesTrait {

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $dexterousness = 10;

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
    protected $agility = 10;

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
    protected $precision = 10;

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
    protected $reputation = 0;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $charme = 0;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $skillPoints = 0;

    public function getAttribute(int $attribute): int {
        switch ($attribute) {
            case Attribute::STRENGTH:
                return $this->getStrength();
            case Attribute::PRECISION:
                return $this->getPrecision();
            case Attribute::DEXTEROUSNESS:
                return $this->getDexterousness();
            case Attribute::CONSTITUTION:
                return $this->getConstitution();
            case Attribute::AGILITY:
                return $this->getAgility();
            case Attribute::INTELLIGENCE:
                return $this->getIntelligence();
        }
        return 0;
    }

    public function setAttribute(int $attribute, int $value) {
        if ($value > 100) {
            $value = 100;
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

    function getDexterousness() {
        return $this->dexterousness;
    }

    function getConstitution() {
        return $this->constitution;
    }

    function getAgility() {
        return $this->agility;
    }

    function getIntelligence() {
        return $this->intelligence;
    }

    function getPrecision() {
        return $this->precision;
    }

    function getStrength() {
        return $this->strength;
    }

    function getReputation() {
        return $this->reputation;
    }

    function getCharme() {
        return $this->charme;
    }

    function getSkillPoints() {
        return $this->skillPoints;
    }

    function setDexterousness($dexterousness) {
        $this->dexterousness = $dexterousness;
    }

    function setConstitution($constitution) {
        $this->constitution = $constitution;
    }

    function setAgility($agility) {
        $this->agility = $agility;
    }

    function setIntelligence($intelligence) {
        $this->intelligence = $intelligence;
    }

    function setPrecision($precision) {
        $this->precision = $precision;
    }

    function setStrength($strength) {
        $this->strength = $strength;
    }

    function setReputation($reputation) {
        $this->reputation = $reputation;
    }

    function setCharme($charme) {
        $this->charme = $charme;
    }

    function setSkillPoints($skillPoints) {
        $this->skillPoints = $skillPoints;
    }

    private function set(int $attribute, int $value) {
        switch ($attribute) {
            case Attribute::STRENGTH:
                $this->strength = $value;
                break;
            case Attribute::PRECISION:
                $this->precision = $value;
                break;
            case Attribute::DEXTEROUSNESS:
                $this->dexterousness = $value;
                break;
            case Attribute::CONSTITUTION:
                $this->constitution = $value;
                break;
            case Attribute::AGILITY:
                $this->agility = $value;
                break;
            case Attribute::INTELLIGENCE:
                $this->intelligence = $value;
                break;
        }
    }

}
