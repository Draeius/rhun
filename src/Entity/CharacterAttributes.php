<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

/**
 * Description of CharacterAttributes
 *
 * @author Draeius
 * @Entity
 */
class CharacterAttributes extends AttributeWrapper {

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
        return parent::getAttribute($attribute);
    }

    public function getId() {
        return $this->id;
    }

    public function getReputation() {
        return $this->reputation;
    }

    public function getCharme() {
        return $this->charme;
    }

    public function getStrength() {
        return $this->strength;
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

    public function getSkillPoints() {
        return $this->skillPoints;
    }

    public function setReputation($reputation) {
        $this->reputation = $reputation;
    }

    public function setCharme($charme) {
        $this->charme = $charme;
    }

    public function setStrength($strength) {
        $this->strength = $strength;
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

    public function setSkillPoints($skillPoints) {
        $this->skillPoints = $skillPoints;
    }

}
