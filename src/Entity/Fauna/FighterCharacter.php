<?php

namespace App\Entity\Fauna;

use App\Entity\Attribute;
use App\Entity\Buff;
use App\Entity\BuffTemplate;
use App\Entity\Items\Armor;
use App\Entity\Items\Weapon;
use App\Entity\Spell;
use App\Entity\Traits\EntityAttributesTrait;
use App\Entity\Traits\EntityHealthTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Description of FighterCharacter
 *
 * @author Draeius
 */
class FighterCharacter extends RPCharacter {

    use EntityAttributesTrait;
    use EntityHealthTrait;

    /**
     * @var Buff[]
     * @OneToMany(targetEntity="Buff", mappedBy="owner", fetch="EXTRA_LAZY", cascade={"remove", "persist"})
     */
    protected $buffs;

    /**
     * The characters current hp
     * @var int 
     * @Column(type="integer") 
     */
    protected $currentHP = 100;

    /**
     * The characters maximum stamina
     * @var int
     * @Column(type="integer")
     */
    protected $maxStamina = 100;

    /**
     * The characters remaining stamina
     * @var int
     * @Column(type="integer")
     */
    protected $currentStamina = 100;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $currentMP = 100;

    /**
     * The characters maximum mp
     * @var int
     * @Column(type="integer")
     */
    protected $maxMP = 100;

    /**
     * The character's weapon
     * @var Weapon  
     * @ManyToOne(targetEntity="App\Entity\Items\Weapon", cascade={"persist"})
     * @JoinColumn(name="weapon_id", referencedColumnName="id")
     */
    protected $weapon;

    /**
     * The character's armor
     * @var Armor 
     * @ManyToOne(targetEntity="App\Entity\Items\Armor", cascade={"persist"})
     * @JoinColumn(name="armor_id", referencedColumnName="id")
     */
    protected $armor;

    /**
     *
     * @var boolean
     * @Column(type="boolean", nullable=false)
     */
    protected $dead = false;

    /**
     * 
     * @var Spell[] 
     * @ManyToMany(targetEntity="Spell", cascade={"remove", "persist"})
     * @JoinTable(name="character_spells",
     *      joinColumns={@JoinColumn(name="character_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="spell_id", referencedColumnName="id")}
     *      )
     */
    protected $spells;

    /**
     *
     * @var StatBuff[]
     */
    private $buffList = null;

    public function __construct() {
        parent::__construct();
        $this->buffs = new ArrayCollection();
        $this->spells = new ArrayCollection();
        $this->buildBuffList();
    }

    public function buildBuffList(): void {
        $this->buffList = BuffFactory::BUFF_LIST_FACTORY(BuffFactory::STAT_BUFFS, $this->buffs);
    }

    public function getMaxHPWithBuff() {
        return $this->calculateBuffedStat($this->maxHP, Attribute::HEALTH_POINTS);
    }

    public function getMaxStaminaWithBuff() {
        return $this->calculateBuffedStat($this->maxStamina, Attribute::STAMINA_POINTS);
    }

    public function getMaxMPWithBuff() {
        return $this->calculateBuffedStat($this->maxMP, Attribute::MAGIC_POINTS);
    }

    public function getAttributeWithBuff(int $attribute): int {
        return $this->calculateBuffedStat($this->getAttribute($attribute), $attribute);
    }

    private function calculateBuffedStat(int $value, int $attribute): int {
        if ($this->buffList == null) {
            $this->buildBuffList();
        }
        $calculator = new StatCalculator($this->buffList);

        return $calculator->calculateAttribute($attribute, $value);
    }

    /**
     * Fügt einen neuen Zauber hinzu, wenn der Charakter diesen nicht schon hat.
     * @param \App\Entity\Fauna\Spell $spell
     * @return bool true, wenn der Zauber hinzugefügt wurde.
     */
    public function addSpell(Spell $spell): bool {
        if (!$this->hasSpell($spell)) {
            $this->spells[] = $spell;
            return true;
        }
        return false;
    }

    public function hasSpell(Spell $spell) {
        foreach ($this->spells as $known) {
            if ($spell->getId() == $known->getId()) {
                return true;
            }
        }
        return false;
    }

    public function addHP($amount) {
        $this->currentHP = $this->addToValue($amount, $this->currentHP, $this->getMaxHPWithBuff());
    }

    public function addStamina($amount) {
        $this->currentStamina = $this->addToValue($amount, $this->currentStamina, $this->getMaxStaminaWithBuff());
    }

    public function addMP($amount) {
        $this->currentMP = $this->addToValue($amount, $this->currentMP, $this->getMaxMPWithBuff());
    }

    public function findBuffByTemplate(BuffTemplate $buff): ?Buff {
        foreach ($this->buffs as $element) {
            if ($buff->getId() == $element->getTemplate()->getId()) {
                return $element;
            }
        }
        return false;
    }

    /**
     * Findet den Buff, aus der Reihe des gegebenen Buffs, den der Spieler aktuell hat.
     * 
     * @param Buff $buff
     * @return Buff|null
     */
    public function getCurrentBuff(Buff $buff): ?Buff {
        foreach ($this->buffs as $element) {
            if ($buff->getTemplate()->getId() - $buff->getTemplate()->getLevel() == $element->getTemplate()->getId() - $element->getTemplate()->getLevel()) {
                return $element;
            }
        }
        return null;
    }

    public function addBuff(Buff &$buff) {
        $current = $this->getCurrentBuff($buff);
        if (!$current) {
            $this->addBuffToList($buff);
            return;
        }
        if ($current->isHigherTier($buff)) {
            return;
        }
        if ($current->isLowerTier($buff) || $current->getEndDate() <= $buff->getEndDate()) {
            $this->removeBuff($current);
            $this->addBuffToList($buff);
        }
    }

    private function addBuffToList(Buff $buff): void {
        $this->buffs[] = $buff;
        $buff->setOwner($this);
        $this->processBuff($buff, false);
    }

    public function removeBuff(Buff &$buff) {
        $this->buffs->removeElement($buff);
        $this->processBuff($buff, true);
        $buff->setOwner(null);
    }

    private function processBuff(Buff $buff, $remove) {
        $modifier = $remove ? -1 : 1;
        if ($buff->isPercentageStaminaBuff()) {
            $this->addStamina($buff->getTemplate()->getStaminaBuff() * $this->maxStamina * $modifier);
        } else {
            $this->addStamina($buff->getTemplate()->getStaminaBuff() * $modifier);
        }
        if ($buff->isPercentageHPBuff()) {
            $this->addHP($buff->getTemplate()->getHpBuff() * $this->maxHP * $modifier);
        } else {
            $this->addHP($buff->getTemplate()->getHpBuff() * $modifier);
        }
        if ($buff->isPercentageMPBuff()) {
            $this->addMP($buff->getTemplate()->getMpBuff() * $this->maxMP * $modifier);
        } else {
            $this->addMP($buff->getTemplate()->getMpBuff() * $modifier);
        }
        $this->buildBuffList();
    }

    function getBuffs(): array {
        return $this->buffs;
    }

    function getCurrentHP() {
        return $this->currentHP;
    }

    function getMaxStamina() {
        return $this->maxStamina;
    }

    function getCurrentStamina() {
        return $this->currentStamina;
    }

    function getCurrentMP() {
        return $this->currentMP;
    }

    function getMaxMP() {
        return $this->maxMP;
    }

    function getWeapon(): Weapon {
        return $this->weapon;
    }

    function getArmor(): Armor {
        return $this->armor;
    }

    function getDead() {
        return $this->dead;
    }

    function getSpells(): array {
        return $this->spells;
    }

    function getBuffList(): array {
        return $this->buffList;
    }

    function setBuffs(array $buffs) {
        $this->buffs = $buffs;
    }

    function setCurrentHP($currentHP) {
        $this->currentHP = $currentHP;
    }

    function setMaxStamina($maxStamina) {
        $this->maxStamina = $maxStamina;
    }

    function setCurrentStamina($currentStamina) {
        $this->currentStamina = $currentStamina;
    }

    function setCurrentMP($currentMP) {
        $this->currentMP = $currentMP;
    }

    function setMaxMP($maxMP) {
        $this->maxMP = $maxMP;
    }

    function setWeapon(Weapon $weapon) {
        $this->weapon = $weapon;
    }

    function setArmor(Armor $armor) {
        $this->armor = $armor;
    }

    function setDead($dead) {
        $this->dead = $dead;
    }

    function setSpells(array $spells) {
        $this->spells = $spells;
    }

    function setBuffList(array $buffList) {
        $this->buffList = $buffList;
    }

}
