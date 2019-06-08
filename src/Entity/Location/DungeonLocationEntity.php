<?php

namespace App\Entity\Location;

use AppBundle\Entity\Item;
use AppBundle\Util\Config\Config;
use AppBundle\Util\Price;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use NavigationBundle\Location\DungeonLocation;
use NavigationBundle\Location\FightingLocation;

/**
 * Description of DungeonLocation
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_dungeon")
 */
class DungeonLocationEntity extends FightingLocationEntity {

    /**
     *
     * @var DungeonLocationEntity[]
     * @ManyToMany(targetEntity="DungeonLocationEntity", fetch="EXTRA_LAZY")
     * @JoinTable(name="dungeon_reward_items",
     *      joinColumns={@JoinColumn(name="location_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="next_location_id", referencedColumnName="id")}
     *      )
     */
    protected $nextLocations;

    /**
     * The target of this nav
     * @var DungeonLocationEntity
     * @ManyToOne(targetEntity="DungeonLocationEntity", inversedBy="incomingNavs", fetch="EXTRA_LAZY")
     * @JoinColumn(name="target_location_id", referencedColumnName="id", nullable=true)
     */
    protected $entrance;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $monsterCount;

    /**
     *
     * @var int
     * Column(type="integer")
     */
    protected $rewardGold = 0;

    /**
     *
     * @var int
     * Column(type="integer")
     */
    protected $rewardPlatin = 0;

    /**
     *
     * @var int
     * Column(type="integer")
     */
    protected $rewardGems = 0;

    /**
     * The item that is wrapped by this InventoryItem
     * @var Item 
     * @ManyToMany(targetEntity="AppBundle\Entity\Item")
     * @JoinTable(name="dungeon_reqard_items",
     *      joinColumns={@JoinColumn(name="location_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="item_id", referencedColumnName="id")}
     *      )
     */
    protected $rewardItems;

    /**
     *
     * @var int
     * Column(type="integer")
     */
    protected $minLevel = 0;

    /**
     *
     * @var int
     * Column(type="integer")
     */
    protected $maxlevel = 50;

    public function __construct() {
        parent::__construct();
        $this->rewardItems = new ArrayCollection();
        $this->nextLocations = new ArrayCollection();
    }

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config): FightingLocation {
        return new DungeonLocation($manager, $uuid, $this, $config);
    }

    public function getReward(): Price {
        return new Price($this->rewardGold, $this->rewardPlatin, $this->rewardGems);
    }

    public function getMonsterCount() {
        return $this->monsterCount;
    }

    public function getNextLocations() {
        return $this->nextLocations;
    }

    public function getRewardGold() {
        return $this->rewardGold;
    }

    public function getRewardPlatin() {
        return $this->rewardPlatin;
    }

    public function getRewardGems() {
        return $this->rewardGems;
    }

    public function getRewardItems() {
        return $this->rewardItems;
    }

    public function getMinLevel() {
        return $this->minLevel;
    }

    public function getMaxlevel() {
        return $this->maxlevel;
    }

    public function getEntrance(): DungeonLocationEntity {
        return $this->entrance;
    }

    public function setNextLocations($nextLocations) {
        $this->nextLocations = $nextLocations;
    }

    public function setRewardGold($rewardGold) {
        $this->rewardGold = $rewardGold;
    }

    public function setRewardPlatin($rewardPlatin) {
        $this->rewardPlatin = $rewardPlatin;
    }

    public function setRewardGems($rewardGems) {
        $this->rewardGems = $rewardGems;
    }

    public function setRewardItems($rewardItems) {
        $this->rewardItems = $rewardItems;
    }

    public function setMinLevel($minLevel) {
        $this->minLevel = $minLevel;
    }

    public function setMaxlevel($maxlevel) {
        $this->maxlevel = $maxlevel;
    }

    public function setMonsterCount($monsterCount) {
        $this->monsterCount = $monsterCount;
    }

}
