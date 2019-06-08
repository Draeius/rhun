<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of Buff
 *
 * @author Draeius
 * @Entity
 * @Table(name="buff_templates")
 */
class BuffTemplate {

    /**
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * 
     * @var string 
     * @Column(type="string", length=64)
     */
    protected $name;

    /**
     * 
     * @var string 
     * @Column(type="text")
     */
    protected $description;

    /**
     * 
     * @var int 
     * @Column(type="integer")
     */
    protected $level;

    /**
     * 
     * @var boolean
     * @Column(type="boolean")
     */
    protected $endOnDeath = false;

    /**
     * 
     * @var boolean
     * @Column(type="boolean")
     */
    protected $endOnNewDay = true;

    /**
     * 
     * @var string 
     * @Column(type="string", length=64)
     */
    protected $icon;

    /**
     * 
     * @var array 
     * @Column(type="json_array")
     */
    protected $statBuff = [];

    /**
     * 
     * @var float 
     * @Column(type="float")
     */
    protected $hpBuff = 0;

    /**
     * 
     * @var float 
     * @Column(type="float")
     */
    protected $hpRegen = 0;

    /**
     * 
     * @var float 
     * @Column(type="float")
     */
    protected $staminaBuff = 0;

    /**
     * 
     * @var float 
     * @Column(type="float")
     */
    protected $staminaRegen = 0;

    /**
     * 
     * @var float 
     * @Column(type="float")
     */
    protected $mpBuff = 0;

    /**
     * 
     * @var float 
     * @Column(type="float")
     */
    protected $mpRegen = 0;

    /**
     * 
     * @var boolean 
     * @Column(type="boolean")
     */
    protected $canWork = false;

    /**
     * 
     * @var float 
     * @Column(type="float")
     */
    protected $attackBuff = 0;

    /**
     * 
     * @var float 
     * @Column(type="float")
     */
    protected $defenseBuff = 0;

    /**
     * 
     * @var float 
     * @Column(type="integer")
     */
    protected $staminaUsageCap = -1;

    public function __construct() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getLevel() {
        return $this->level;
    }

    public function getEndOnDeath() {
        return $this->endOnDeath;
    }

    public function getIcon() {
        return $this->icon;
    }

    public function getStatBuff() {
        return $this->statBuff;
    }

    public function getHpBuff() {
        return $this->hpBuff;
    }

    public function getStaminaBuff() {
        return $this->staminaBuff;
    }

    public function getCanWork() {
        return $this->canWork;
    }

    public function getAttackBuff() {
        return $this->attackBuff;
    }

    public function getDefenseBuff() {
        return $this->defenseBuff;
    }

    public function getHpRegen() {
        return $this->hpRegen;
    }

    public function getStaminaRegen() {
        return $this->staminaRegen;
    }

    public function getEndOnNewDay() {
        return $this->endOnNewDay;
    }

    public function getMpBuff() {
        return $this->mpBuff;
    }

    public function getMpRegen() {
        return $this->mpRegen;
    }

    public function getStaminaUsageCap() {
        return $this->staminaUsageCap;
    }

    public function setStaminaUsageCap($StaminaUsageCap) {
        $this->staminaUsageCap = $StaminaUsageCap;
    }

    public function setMpBuff($mpBuff) {
        $this->mpBuff = $mpBuff;
    }

    public function setMpRegen($mpRegen) {
        $this->mpRegen = $mpRegen;
    }

    public function setEndOnNewDay($endOnNewDay) {
        $this->endOnNewDay = $endOnNewDay;
    }

    public function setHpRegen($hpRegen) {
        $this->hpRegen = $hpRegen;
    }

    public function setStaminaRegen($staminaRegen) {
        $this->staminaRegen = $staminaRegen;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setLevel($level) {
        $this->level = $level;
    }

    public function setEndOnDeath($endOnDeath) {
        $this->endOnDeath = $endOnDeath;
    }

    public function setIcon($icon) {
        $this->icon = $icon;
    }

    public function setStatBuff($statBuff) {
        $this->statBuff = $statBuff;
    }

    public function setHpBuff($hpBuff) {
        $this->hpBuff = $hpBuff;
    }

    public function setStaminaBuff($staminaBuff) {
        $this->staminaBuff = $staminaBuff;
    }

    public function setCanWork($canWork) {
        $this->canWork = $canWork;
    }

    public function setAttackBuff($attackBuff) {
        $this->attackBuff = $attackBuff;
    }

    public function setDefenseBuff($defenseBuff) {
        $this->defenseBuff = $defenseBuff;
    }

}
