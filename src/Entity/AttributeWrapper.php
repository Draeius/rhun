<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\MappedSuperclass;

/**
 * Description of AttributeWrapper
 *
 * @author Draeius
 * @MappedSuperclass
 */
class AttributeWrapper {

    /**
     * @var int 
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

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

    public function getId() {
        return $this->id;
    }

    public function getDexterousness() {
        return $this->dexterousness;
    }

    public function getConstitution() {
        return $this->constitution;
    }

    public function getAgility() {
        return $this->agility;
    }

    public function getIntelligence() {
        return $this->intelligence;
    }

    public function getPrecision() {
        return $this->precision;
    }

    public function getStrength() {
        return $this->strength;
    }

    public function setDexterousness($dexterousness) {
        $this->dexterousness = $dexterousness;
    }

    public function setConstitution($constitution) {
        $this->constitution = $constitution;
    }

    public function setAgility($agility) {
        $this->agility = $agility;
    }

    public function setIntelligence($intelligence) {
        $this->intelligence = $intelligence;
    }

    public function setPrecision($precision) {
        $this->precision = $precision;
    }

    public function setStrength($strength) {
        $this->strength = $strength;
    }

}
