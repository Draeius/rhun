<?php

namespace App\Entity;

use App\Entity\Item;
use App\Entity\Items\Item as Item2;
use App\Entity\Traits\EntityAttributesTrait;
use App\Entity\Traits\EntityColoredNameTrait;
use App\Entity\Traits\EntityHealthTrait;
use App\Util\Fight\Action\Action;
use App\Util\Fight\Action\AttackAction;
use App\Util\Fight\Action\FleeAction;
use App\Util\Fight\Damage;
use App\Util\Fight\Dice;
use App\Util\Fight\FighterInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

/**
 * An enemy for fights
 * 
 * @author Draeius
 * @Entity
 * @Table(name="monsters")
 */
class Monster extends RhunEntity implements FighterInterface {

    use EntityColoredNameTrait;
    use EntityHealthTrait;
    use EntityAttributesTrait;

    /**
     * @var string
     * @Column(type="string")
     */
    protected $attackRoll;

    /**
     * @var string
     * @Column(type="string")
     */
    protected $damageRoll;

    /**
     * @var int
     * @Column(type="int")
     */
    protected $armorClass;

    /**
     * @var string
     * @Column(type="string", length=64)
     */
    protected $weaponName;

    /**
     * @var string
     * @Column(type="string", length=64)
     */
    protected $armorName;

    /**
     * Die Resistenzen dieser Rüstung
     *
     * @var Collection|array
     * @ManyToMany(targetEntity="DamageType")
     * @JoinTable(name="armor_resistances",
     *      joinColumns={@JoinColumn(name="armor_templ_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="damage_type_id", referencedColumnName="id")}
     *      )
     */
    protected $resistances;

    /**
     * Die Verwundbarkeiten dieser Rüstung
     *
     * @var Collection|array
     * @ManyToMany(targetEntity="DamageType")
     * @JoinTable(name="armor_vulnerabilities",
     *      joinColumns={@JoinColumn(name="armor_templ_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="damage_type_id", referencedColumnName="id")}
     *      )
     */
    protected $vulnerabilities;

    /**
     *
     * @var DamageType
     * @ManyToOne(targetEntity="DamageType")
     * @JoinColumn(name="damage_type_id", referencedColumnName="id")
     */
    protected $damageType;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $level;

    /**
     * The maximum amount of platin this monster drops
     * @var int
     * @deprecated since version number
     * @Column(type="integer") 
     */
    protected $lootPlatin = 0;

    /**
     * The maximum amount of gold this monster drops
     * @var int
     * @Column(type="integer") 
     */
    protected $lootGold = 0;

    /**
     * The items this monster can drop.
     * @var Item[]
     * @ManyToMany(targetEntity="Item2", fetch="EXTRA_LAZY")
     * @JoinTable(name="monster_drops",
     *      joinColumns={@JoinColumn(name="monster_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="item_id", referencedColumnName="id")}
     *      )
     */
    protected $drops = [];

    /**
     * The occurances of this enemy
     * @var MonsterOccurance[]
     * @OneToMany(targetEntity="MonsterOccurance", mappedBy="monster", fetch="EXTRA_LAZY")
     */
    protected $occurances;

    /**
     * @var string
     * @Column(type="string", length=256)
     */
    protected $victorySentence;

    /**
     * @var string
     * @Column(type="string", length=256)
     */
    protected $loseSentence;

    /**
     * @var bool
     * @Column(type="boolean")
     */
    protected $allowFlee;
    private $currentHP;

    public function __construct() {
        $this->occurances = new ArrayCollection();
    }

    /**
     * Selects the item that is dropped by this monster.
     * @return Item The selected item
     */
    public function chooseDroppedItem() {
        $total = 0;
        foreach ($this->drops as $item) {
            $total += 1 / ($item->getPriceGold() + $item->getPricePlatin() + $item->getPriceGems());
        }
        if ($total == 0) {
            return null;
        }
        $rand = rand(0, $total);
        $counter = 0;
        foreach ($this->drops as $item) {
            $counter += 1 / ($item->getPriceGold() + $item->getPricePlatin() + $item->getPriceGems());
            if ($rand <= $counter) {
                return $item;
            }
        }
    }

    public function calculateEXP(): int {
        return round(($this->level / 4) * 100);
    }

    function getAttackRoll() {
        return $this->attackRoll;
    }

    function getDamageRoll() {
        return $this->damageRoll;
    }

    function getArmorClass() {
        return $this->armorClass;
    }

    function getWeaponName() {
        return $this->weaponName;
    }

    function getArmorName() {
        return $this->armorName;
    }

    function getLevel() {
        return $this->level;
    }

    function getLootPlatin() {
        return $this->lootPlatin;
    }

    function getLootGold() {
        return $this->lootGold;
    }

    function getDrops(): array {
        return $this->drops;
    }

    function getOccurances(): array {
        return $this->occurances;
    }

    function getVictorySentence() {
        return $this->victorySentence;
    }

    function getLoseSentence() {
        return $this->loseSentence;
    }

    function getVulnerabilities() {
        return $this->vulnerabilities;
    }

    function getDamageType(): DamageType {
        return $this->damageType;
    }

    function getCurrentHP() {
        return $this->currentHP;
    }

    function getAllowFlee() {
        return $this->allowFlee;
    }

    function setAllowFlee($allowFlee) {
        $this->allowFlee = $allowFlee;
    }

    function setCurrentHP($currentHP) {
        $this->currentHP = $currentHP;
    }

    function setVulnerabilities($vulnerabilities) {
        $this->vulnerabilities = $vulnerabilities;
    }

    function setDamageType(DamageType $damageType) {
        $this->damageType = $damageType;
    }

    function setResistances($resistances) {
        $this->resistances = $resistances;
    }

    function setAttackRoll($attackRoll) {
        $this->attackRoll = $attackRoll;
    }

    function setDamageRoll($damageRoll) {
        $this->damageRoll = $damageRoll;
    }

    function setArmorClass($armorClass) {
        $this->armorClass = $armorClass;
    }

    function setWeaponName($weaponName) {
        $this->weaponName = $weaponName;
    }

    function setArmorName($armorName) {
        $this->armorName = $armorName;
    }

    function setLevel($level) {
        $this->level = $level;
    }

    function setLootPlatin($lootPlatin) {
        $this->lootPlatin = $lootPlatin;
    }

    function setLootGold($lootGold) {
        $this->lootGold = $lootGold;
    }

    function setDrops(array $drops) {
        $this->drops = $drops;
    }

    function setOccurances(array $occurances) {
        $this->occurances = $occurances;
    }

    function setVictorySentence($victorySentence) {
        $this->victorySentence = $victorySentence;
    }

    function setLoseSentence($loseSentence) {
        $this->loseSentence = $loseSentence;
    }

    public function getAction(array $participants): ?Action {
        if ($this->allowFlee && $this->currentHP < $this->maxHP * 0.1) {
            return new FleeAction($this, 0, 0);
        }
        foreach ($participants as $key => $value) {
            if ($value->getFighter() instanceof Character) {
                return new AttackAction($this, $key, 0);
            }
        }
    }

    public function getAttackDice(): Dice {
        return Dice::FACTORY($this->attackRoll);
    }

    public function getDamage(): Damage {
        return new Damage(Dice::FACTORY($this->damageRoll), $this->damageType);
    }

    public function getResistances() {
        return $this->resistances;
    }

    public function getVulnerabilitiers() {
        return $this->vulnerabilities;
    }

    public function getAttributeDice(int $attribute): Dice {
        return new Dice(20);
    }

}
