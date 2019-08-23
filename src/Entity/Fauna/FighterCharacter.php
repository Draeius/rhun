<?php

namespace App\Entity\Fauna;

use App\Entity\ArmorType;
use App\Entity\Attribute;
use App\Entity\Buff;
use App\Entity\BuffTemplate;
use App\Entity\Items\Armor;
use App\Entity\Items\Weapon;
use App\Entity\Spell;
use App\Entity\Traits\EntityAttributesTrait;
use App\Entity\Traits\EntityHealthTrait;
use App\Entity\Traits\EntityLevelTrait;
use App\Entity\WeaponType;
use App\Util\Fight\Action\Action;
use App\Util\Fight\Damage;
use App\Util\Fight\Dice;
use App\Util\Fight\DiceMode;
use App\Util\Fight\FighterInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
class FighterCharacter extends RPCharacter implements FighterInterface {

    use EntityAttributesTrait;
    use EntityLevelTrait;
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
     * The characters remaining stamina
     * @var int
     * @Column(type="integer")
     */
    protected $actionPoints = 0;

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
     * Die Zweitwaffe/Schild dieses Charakters
     * @var Weapon  
     * @ManyToOne(targetEntity="App\Entity\Items\Weapon", cascade={"persist"})
     * @JoinColumn(name="weapon_id", referencedColumnName="id", nullable=true)
     */
    protected $offhandWeapon;

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

    public function __construct() {
        parent::__construct();
        $this->buffs = new ArrayCollection();
        $this->spells = new ArrayCollection();
    }

    public function getMaxHPWithBuff() {
        return $this->calculateBuffedStat($this->maxHP, Attribute::HEALTH_POINTS);
    }

    public function getMaxMPWithBuff() {
        return $this->calculateBuffedStat($this->maxMP, Attribute::MAGIC_POINTS);
    }

    public function getAttributeWithBuff(int $attribute): int {
        return $this->calculateBuffedStat($this->getAttribute($attribute), $attribute);
    }

    private function calculateBuffedStat(int $value, int $attribute): int {
//        if ($this->buffList == null) {
//            $this->buildBuffList();
//        }
//        $calculator = new StatCalculator($this->buffList);

        return $value; // $calculator->calculateAttribute($attribute, $value);
    }

    /**
     * Fügt einen neuen Zauber hinzu, wenn der Charakter diesen nicht schon hat.
     * @param \App\Entity\Fauna\Spell $spell
     * @return bool true, wenn der Zauber hinzugefügt wurde.
     */
    public function addSpell(Spell $spell): bool {
        if (!$this->knowsSpell($spell)) {
            $this->spells[] = $spell;
            return true;
        }
        return false;
    }

    public function knowsSpell(Spell $spell) {
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

    public function addActionPoints($amount) {
        $this->actionPoints = $this->addToValue($amount, $this->actionPoints, 999999);
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

    public function levelUp(): void {
        $this->level ++;
    }

    function getBuffs(): Collection {
        return $this->buffs;
    }

    function getCurrentHP() {
        return $this->currentHP;
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

    function getActionPoints() {
        return $this->actionPoints;
    }

    function setActionPoints($actionPoints) {
        $this->actionPoints = $actionPoints;
    }

    function setBuffs(array $buffs) {
        $this->buffs = $buffs;
    }

    function setCurrentHP($currentHP) {
        $this->currentHP = $currentHP;
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

    public function getAction(array $participants): ?Action {
        //only needed for PvP, wich is not yet implemented
        return null;
    }

    public function getArmorClass(): int {
        $modifier = Attribute::GET_ABILITY_MODIFIER($this->getAttributeWithBuff(Attribute::DEXTERITY));
        $shield = $this->getOffhandDefBonus();

        $armorTemplate = $this->getArmor()->getArmorTemplate();
        switch ($armorTemplate->getArmorType()) {
            case ArmorType::LIGHT:
                return $armorTemplate->getDefense() + $modifier + $shield;
            case ArmorType::MIDDLE:
                if ($modifier > 2) {
                    $modifier = 2;
                }
                return $armorTemplate->getDefense() + $modifier + $shield;
            case ArmorType::HEAVY:
                return $armorTemplate->getDefense() + $shield;
        }
    }

    public function getAttributeDice(int $attribute): Dice {
        return new Dice(20);
    }

    public function getAttackDice(): Dice {
        return new Dice(20, 1, $this->getAtkDiceModifier(), $this->getAtkDiceMode());
    }

    public function getDamage(): Damage {
        $mod = $this->getAtkDiceModifier();
        $diceMode = $this->getAtkDiceMode();

        $dice = Dice::FACTORY($this->weapon->getWeaponTemplate()->getDamage());
        $dice->addToModificator($mod);
        $dice->setDiceMode($diceMode);

        return new Damage($dice, $this->weapon->getWeaponTemplate()->getDamageType());
    }

    public function getResistances() {
        return $this->armor->getArmorTemplate()->getResistances();
    }

    public function getVulnerabilitiers() {
        return $this->armor->getArmorTemplate()->getVulnerabilities();
    }

    private function getOffhandDefBonus() {
        $shield = $this->offhandWeapon->getWeaponTemplate();
        if (!$this->offhandWeapon) {
            return 0;
        }
        if ($this->getAttributeWithBuff($shield->getAttribute()) < $shield->getMinAttribute()) {
            return -2;
        }
        return $shield->getOffhandDefBonus();
    }

    private function getAtkDiceModifier() {
        switch ($this->weapon->getWeaponTemplate()->getWeaponType()) {
            case WeaponType::BOW:
            case WeaponType::FOCUS:
            case WeaponType::STAFF:
                return Attribute::GET_ABILITY_MODIFIER($this->getAttributeWithBuff(Attribute::DEXTERITY));
        }
        return Attribute::GET_ABILITY_MODIFIER($this->getAttributeWithBuff(Attribute::STRENGTH));
    }

    private function getAtkDiceMode() {
        $armor = $this->armor->getArmorTemplate();
        if ($this->getAttributeWithBuff($armor->getAttribute()) < $armor->getMinAttribute()) {
            return DiceMode::DISADVANTAGE;
        }

        $weapon = $this->weapon->getWeaponTemplate();
        if ($this->getAttributeWithBuff($weapon->getAttribute()) < $weapon->getMinAttribute()) {
            return DiceMode::DISADVANTAGE;
        }

        if ($this->offhandWeapon) {
            $offhand = $this->offhandWeapon->getWeaponTemplate();
            if ($this->getAttributeWithBuff($offhand->getAttribute()) < $offhand->getMinAttribute()) {
                return DiceMode::DISADVANTAGE;
            }
        }
        return DiceMode::NONE;
    }

}
