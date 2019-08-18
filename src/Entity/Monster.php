<?php

namespace App\Entity;

use App\Entity\Item;
use App\Entity\Traits\EntityColoredNameTrait;
use App\Entity\Traits\EntityHealthTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

/**
 * An enemy for fights
 * 
 * @author Draeius
 * @Entity
 * @Table(name="monsters")
 */
class Monster extends RhunEntity {

    use EntityColoredNameTrait;
    use EntityHealthTrait;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $attack;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $defense;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $critChance;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $critMulti;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $evadeChance;

    /**
     * 
     * @var string
     * @Column(type="integer")
     */
    protected $monsterRank;

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
     * @ManyToMany(targetEntity="App\Entity\Items\Item", fetch="EXTRA_LAZY")
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
            $total += $item->getBuyPrice();
        }
        if ($total == 0) {
            return null;
        }
        $rand = rand(0, $total);
        $counter = 0;
        foreach ($this->drops as $item) {
            $counter += $item->getBuyPrice();
            if ($rand <= $counter) {
                return $item;
            }
        }
    }

    public function getAttack() {
        return $this->attack;
    }

    public function getDefense() {
        return $this->defense;
    }

    public function getMonsterRank() {
        return $this->monsterRank;
    }

    public function getWeaponName() {
        return $this->weaponName;
    }

    public function getArmorName() {
        return $this->armorName;
    }

    public function getLevel() {
        return $this->level;
    }

    public function getLootPlatin() {
        return $this->lootPlatin;
    }

    public function getLootGold() {
        return $this->lootGold;
    }

    public function getDrops(): array {
        return $this->drops;
    }

    public function getOccurances(): array {
        return $this->occurances;
    }

    public function getVictorySentence() {
        return $this->victorySentence;
    }

    public function getLoseSentence() {
        return $this->loseSentence;
    }

    public function getCritChance() {
        return $this->critChance;
    }

    public function getCritMulti() {
        return $this->critMulti;
    }

    public function getEvadeChance() {
        return $this->evadeChance;
    }

    public function setEvadeChance($evadeChance) {
        $this->evadeChance = $evadeChance;
    }

    public function setCritMulti($critMulti) {
        $this->critMulti = $critMulti;
    }

    public function setCritChance($critChance) {
        $this->critChance = $critChance;
    }

    public function setAttack($attack) {
        $this->attack = $attack;
    }

    public function setDefense($defense) {
        $this->defense = $defense;
    }

    public function setMonsterRank($rank) {
        $this->monsterRank = $rank;
    }

    public function setWeaponName($weaponName) {
        $this->weaponName = $weaponName;
    }

    public function setArmorName($armorName) {
        $this->armorName = $armorName;
    }

    public function setLevel($level) {
        $this->level = $level;
    }

    public function setLootPlatin($lootPlatin) {
        $this->lootPlatin = $lootPlatin;
    }

    public function setLootGold($lootGold) {
        $this->lootGold = $lootGold;
    }

    public function setDrops(array $drops) {
        $this->drops = $drops;
    }

    public function setOccurances(array $occurances) {
        $this->occurances = $occurances;
    }

    public function setVictorySentence($victorySentence) {
        $this->victorySentence = $victorySentence;
    }

    public function setLoseSentence($loseSentence) {
        $this->loseSentence = $loseSentence;
    }

}
