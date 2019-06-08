<?php

namespace App\Entity;

use App\Entity\Armor;
use App\Entity\Race;
use App\Entity\User;
use App\Entity\Weapon;
use App\Service\DateTimeService;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use App\Entity\Buff;
use App\Entity\BuffTemplate;
use App\Entity\Spell;
use App\Fight\Buff\BuffFactory;
use App\Fight\Buff\StatBuff;
use App\Fight\Calculator\StatCalculator;
use App\Entity\Guild;
use App\Entity\Location\LocationEntity;

/**
 * Description of Character
 *
 * @author Matthias
 * @Entity(repositoryClass="App\Repository\CharacterRepository")
 * @Table(name="characters")
 */
class Character {

    /**
     * The characters id
     * @var int 
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * The character's owning account
     * @var User 
     * @ManyToOne(targetEntity="User", inversedBy="characters")
     * @JoinColumn(name="account_id", referencedColumnName="id")
     */
    protected $account;

    /**
     * The name of this character. This may not contain any colorcodes
     * @var string 
     * @Column(type="string", length=32)
     */
    protected $name;

    /**
     * The characters titles
     * @var string 
     * @OneToMany(targetEntity="ColoredName", mappedBy="owner", fetch="EXTRA_LAZY")
     */
    protected $coloredNames;

    /**
     * @var CharacterAttributes
     * @OneToOne(targetEntity="CharacterAttributes", cascade={"remove", "persist"})
     */
    protected $attributes;

    /**
     * @var Buff[]
     * @OneToMany(targetEntity="App\Entity\Buff", mappedBy="owner", fetch="EXTRA_LAZY", cascade={"remove", "persist"})
     */
    protected $buffs;

    /**
     * The characters level
     * @var int 
     * @Column(type="integer") 
     */
    protected $level = 1;

    /**
     * The characters maximum hp
     * @var int 
     * @Column(type="integer") 
     */
    protected $maxHP = 100;

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
     *
     * @var int
     * @Column(type="integer")
     */
    protected $maxMP = 100;

    /**
     * The characters current exp
     * @var int 
     * @Column(type="integer") 
     */
    protected $exp = 0;

    /**
     * The characters titles
     * @var string 
     * @OneToMany(targetEntity="Title", mappedBy="owner", fetch="EXTRA_LAZY", cascade={"remove"})
     */
    protected $titles;

    /**
     * The character's gender
     * @var bool
     * @Column(type="boolean", nullable=false)
     */
    protected $gender;

    /**
     * The character's race
     * @var Race
     * @ManyToOne(targetEntity="Race")
     * @JoinColumn(name="race_id", referencedColumnName="id")
     */
    protected $race;

    /**
     *
     * @var Wallet
     * @OneToOne(targetEntity="Wallet", mappedBy="owner",  cascade={"remove", "persist"})
     */
    protected $wallet;

    /**
     * @var Job
     * @ManyToOne(targetEntity="Job")
     * @JoinColumn(name="job_id", referencedColumnName="id", nullable=true)
     */
    protected $job;

    /**
     * If this character is considered to be online
     * @var bool 
     * @Column(type="boolean", nullable=false)
     */
    protected $online = false;

    /**
     * If this character is safe from pvp
     * @var bool
     * @Column(type="boolean")
     */
    protected $safe = true;

    /**
     * The last time the player was active
     * @var DateTime 
     * @Column(type="datetime", nullable=false)
     */
    protected $lastActive;

    /**
     * The character's current location 
     * @var LocationEntity 
     * @ManyToOne(targetEntity="App\Entity\Location\LocationEntity")
     * @JoinColumn(name="location_id", referencedColumnName="id")
     */
    protected $location;

    /**
     * This character's biography
     * @var Biography[] 
     * @OneToMany(targetEntity="Biography", mappedBy="owner", fetch="EXTRA_LAZY", cascade={"remove"})
     */
    protected $biography;

    /**
     * This character's biography
     * @var DiaryEntry[] 
     * @OneToMany(targetEntity="DiaryEntry", mappedBy="owner", fetch="EXTRA_LAZY", cascade={"remove"})
     */
    protected $diaryEntries;

    /**
     * The character's weapon
     * @var Weapon  
     * @ManyToOne(targetEntity="Weapon", cascade={"persist"})
     * @JoinColumn(name="weapon_id", referencedColumnName="id")
     */
    protected $weapon;

    /**
     * The character's armor
     * @var Armor 
     * @ManyToOne(targetEntity="Armor", cascade={"persist"})
     * @JoinColumn(name="armor_id", referencedColumnName="id")
     */
    protected $armor;

    /**
     * The character's inventory
     * @var InventoryItem[] 
     * @OneToMany(targetEntity="InventoryItem", mappedBy="owner", cascade={"remove", "persist"}, fetch="EXTRA_LAZY")
     */
    protected $inventory;

    /**
     * The messages this Character has sent
     * @var Message[]
     * @OneToMany(targetEntity="Message", mappedBy="sender", fetch="EXTRA_LAZY", cascade={"remove"})
     */
    protected $messageSent;

    /**
     * The messages this character has received
     * @var Message[]
     * @OneToMany(targetEntity="Message", mappedBy="addressee", fetch="EXTRA_LAZY")
     */
    protected $receivedMessages;

    /**
     *
     * @var boolean
     * @Column(type="boolean", nullable=false)
     */
    protected $dead = false;

    /**
     *
     * @var DateTime
     * @Column(type="datetime", nullable=true)
     */
    protected $ban = null;

    /**
     *
     * @var string
     * @Column(type="text", nullable=true)
     */
    protected $banReason = null;

    /**
     * 
     * @ManyToMany(targetEntity="Quest", fetch="EXTRA_LAZY")
     * @JoinTable(name="finished_quests",
     *      joinColumns={@JoinColumn(name="character_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="quest_id", referencedColumnName="id")}
     *      )
     */
    protected $finishedQuests;

    /**
     *
     * @var boolean
     * @Column(type="boolean", nullable=false)
     */
    protected $hasPreied = false;

    /**
     * 
     * @var int 
     * @Column(type="integer") 
     */
    protected $postCounter = 0;

    /**
     *
     * @var boolean
     * @Column(type="boolean", nullable=false)
     */
    protected $rpState = false;

    /**
     * 
     * @var int 
     * @Column(type="integer") 
     */
    protected $staminaHealed = 0;

    /**
     * @var Character
     * @OneToOne(targetEntity="Character", fetch="EXTRA_LAZY")
     * @JoinColumn(name="partner_id", referencedColumnName="id")
     */
    protected $partner = null;

    /**
     * @var CraftRecipe[]
     * @ManyToMany(targetEntity="CraftRecipe", fetch="EXTRA_LAZY", cascade={"remove", "persist"})
     * @JoinTable(name="character_known_recipes",
     *      joinColumns={@JoinColumn(name="character_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="recipe_id", referencedColumnName="id")}
     *      )
     */
    protected $knownRecipes;

    /**
     * 
     * @var Guild 
     * @ManyToOne(targetEntity="App\Entity\Guild", inversedBy="members")
     * @JoinColumn(name="guild_id", referencedColumnName="id")
     */
    protected $guild;

    /**
     * 
     * @var Spell[] 
     * @ManyToMany(targetEntity="App\Entity\Spell", cascade={"remove", "persist"})
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
        $this->lastActive = DateTimeService::getDateTime("now");
        $this->biography = new ArrayCollection();
        $this->inventory = new ArrayCollection();
        $this->messageSent = new ArrayCollection();
        $this->receivedMessages = new ArrayCollection();
        $this->finishedQuests = new ArrayCollection();
        $this->buffs = new ArrayCollection();
        $this->titles = new ArrayCollection();
        $this->coloredNames = new ArrayCollection();
        $this->diaryEntries = new ArrayCollection();
        $this->spells = new ArrayCollection();
        $this->wallet = new Wallet();
        $this->buildBuffList();
    }

    public function buildBuffList() {
        $this->buffList = BuffFactory::BUFF_LIST_FACTORY(BuffFactory::STAT_BUFFS, $this->buffs);
    }

    public function addSpell(Spell $spell) {
        if (!$this->hasSpell($spell)) {
            $this->spells[] = $spell;
        }
    }

    public function hasSpell(Spell $spell) {
        foreach ($this->spells as $known) {
            if ($spell->getId() == $known->getId()) {
                return true;
            }
        }
        return false;
    }

    public function hasFinishedQuest(Quest $quest) {
        foreach ($this->finishedQuests as $finished) {
            if ($finished->getId() == $quest->getId()) {
                return true;
            }
        }
        return false;
    }

    public function addFinishedQuest(Quest $quest) {
        $this->finishedQuests[] = $quest;
    }

    public function isBanned() {
        if ($this->ban == null) {
            return false;
        }
        return $this->ban > DateTimeService::getDateTime('now');
    }

    public function addHP($amount) {
        $this->currentHP += $amount;
        if ($this->currentHP > $this->getMaxHPWithBuff()) {
            $this->currentHP = $this->getMaxHPWithBuff();
        }
        if ($this->currentHP < 0) {
            $this->currentHP = 0;
        }
    }

    public function addStamina($amount) {
        $this->currentStamina += $amount;
        if ($this->currentStamina > $this->getMaxStaminaWithBuff()) {
            $this->currentStamina = $this->getMaxStaminaWithBuff();
        }
        if ($this->currentStamina < 0) {
            $this->currentStamina = 0;
        }
    }

    public function addMP($amount) {
        $this->currentMP += $amount;
        if ($this->currentMP > $this->getMaxMPWithBuff()) {
            $this->currentMP = $this->getMaxMPWithBuff();
        }
        if ($this->currentMP < 0) {
            $this->currentMP = 0;
        }
    }

    public function addExp($amount) {
        if ($this->level >= 50) {
            return;
        }
        $this->exp += $amount;
        if ($this->exp >= $this->getExpForNextLevel($this->level)) {
            //reset exp
            $this->exp -= $this->getExpForNextLevel();
            //increment level
            $this->level ++;
            $this->maxHP = 50 + 50 * $this->level;
            $this->currentHP = $this->maxHP;

            $this->attributes->setSkillPoints($this->attributes->getSkillPoints() + 6);
        }
    }

    public function getInventoryItemStock(Item $item) {
        if (!$item) {
            return 0;
        }
        foreach ($this->inventory as $invItem) {
            if ($item->getId() == $invItem->getItem()->getId()) {
                return $invItem->getAmount();
            }
        }
        return 0;
    }

    public function addToInventory(Item $item, $amount) {
        //check inventory for items like this one
        foreach ($this->inventory as $invItem) {
            //already have some of this item
            if ($item->getId() == $invItem->getItem()->getId()) {
                //add one
                $invItem->addItem($amount);
                return $invItem;
            }
        }

        $toAdd = new InventoryItem();
        $toAdd->setAmount($amount);
        $toAdd->setItem($item);
        $toAdd->setOwner($this);
        //Add to inventory
        $this->inventory[] = $toAdd;
        return $toAdd;
    }

    public function addReputation($amount) {
        $this->reputation += $amount;
    }

    public function removeFromInventory(Item $item, $amount) {
        if (!$item || !$amount) {
            return;
        }
        foreach ($this->inventory as $invItem) {
            //already have some of this item
            if ($item->getId() == $invItem->getItem()->getId()) {
                //add one
                $invItem->addItem(-1 * $amount);
                return $invItem;
            }
        }
    }

    public function hasItem(Item $item) {
        foreach ($this->inventory as $invItem) {
            if ($item->getId() == $invItem->getItem()->getId()) {
                return true;
            }
        }
        return false;
    }

    public function getActiveBiography(bool $masked = false) {
        foreach ($this->getBiography() as $bio) {
            if ($masked) {
                if ($bio->getMaskedBall()) {
                    return $bio;
                }
            } else {
                if ($bio->getSelected()) {
                    return $bio;
                }
            }
        }
    }

    public function getAttribute(int $attribute): int {
        return $this->attributes->getAttribute($attribute);
    }

    public function getAttributeWithBuff(int $attribute): int {
        if ($this->buffList == null) {
            $this->buildBuffList();
        }
        $value = $this->getAttribute($attribute);
        $calculator = new StatCalculator($this->buffList);

        return $calculator->calculateAttribute($attribute, $value);
    }

    public function getExpForNextLevel() {
        return round(pow($this->level + 4, 2.7) * 3);
    }

    public function addTitle(Title $title) {
        foreach ($this->titles as $oldTitle) {
            if ($oldTitle->getTitle() == $title->getTitle()) {
                return;
            }
        }
        $this->titles[] = $title;
    }

    public function addColoredName(ColoredName $name) {
        foreach ($this->coloredNames as $oldName) {
            if ($oldName->getName() == $name->getName()) {
                return;
            }
        }
        $this->coloredNames[] = $name;
    }

    public function addBuff(Buff &$buff) {
        $current = $this->getCurrentBuff($buff);
        if ($current) {
            if ($current->getTemplate()->getLevel() < $buff->getTemplate()->getLevel()) {
                $this->removeBuff($current);
                $this->buffs[] = $buff;
                $buff->setOwner($this);
                $this->processBuff($buff, false);
                return $current;
            } else if ($current->getTemplate()->getLevel() == $buff->getTemplate()->getLevel()) {
                if ($current->getEndDate() <= $buff->getEndDate()) {
                    $this->removeBuff($current);
                    $this->buffs[] = $buff;
                    $buff->setOwner($this);
                    $this->processBuff($buff, false);
                    return $current;
                }
            }
        } else {
            $this->buffs[] = $buff;
            $buff->setOwner($this);
            $this->processBuff($buff, false);
        }
    }

    public function removeBuff(Buff &$buff) {
        $this->buffs->removeElement($buff);
        $this->processBuff($buff, true);
        $buff->setOwner(null);
    }

    public function getCurrentBuff(Buff $buff) {
        foreach ($this->buffs as $element) {
            if ($buff->getTemplate()->getId() - $buff->getTemplate()->getLevel() == $element->getTemplate()->getId() - $element->getTemplate()->getLevel()) {
                return $element;
            }
        }
        return null;
    }

    public function addKnownRecipe(CraftRecipe $recipe): bool {
        if ($this->hasKnownRecipe($recipe)) {
            return false;
        }
        $this->knownRecipes[] = $recipe;
        return true;
    }

    public function hasKnownRecipe(CraftRecipe $recipe) {
        foreach ($this->knownRecipes as $knownRecipe) {
            if ($knownRecipe->getId() == $recipe->getId()) {
                return true;
            }
        }
        return false;
    }

    public function findBuffByTemplate(BuffTemplate $buff) {
        foreach ($this->buffs as $element) {
            if ($buff->getId() == $element->getTemplate()->getId()) {
                return $element;
            }
        }
        return false;
    }

    public function getMaxHPWithBuff() {
        if ($this->buffList == null) {
            $this->buildBuffList();
        }
        $value = $this->getMaxHP();
        $calculator = new StatCalculator($this->buffList);

        return $calculator->calculateAttribute(Attribute::HEALTH_POINTS, $value);
    }

    public function getMaxStaminaWithBuff() {
        if ($this->buffList == null) {
            $this->buildBuffList();
        }
        $value = $this->getMaxStamina();
        $calculator = new StatCalculator($this->buffList);

        return $calculator->calculateAttribute(Attribute::STAMINA_POINTS, $value);
    }

    public function getMaxMPWithBuff() {
        if ($this->buffList == null) {
            $this->buildBuffList();
        }
        $value = $this->getMaxMP();
        $calculator = new StatCalculator($this->buffList);

        return $calculator->calculateAttribute(Attribute::MAGIC_POINTS, $value);
    }

    private function processBuff(Buff $buff, $remove) {
        $modifier = $remove ? -1 : 1;
        if ($buff->getTemplate()->getStaminaBuff() < 1 && $buff->getTemplate()->getStaminaBuff() > -1) {
            $this->addStamina($buff->getTemplate()->getStaminaBuff() * $this->maxStamina * $modifier);
        } else {
            $this->addStamina($buff->getTemplate()->getStaminaBuff() * $modifier);
        }
        if ($buff->getTemplate()->getHpBuff() < 1 && $buff->getTemplate()->getHpBuff() > -1) {
            $this->addHP($buff->getTemplate()->getHpBuff() * $this->maxHP * $modifier);
        } else {
            $this->addHP($buff->getTemplate()->getHpBuff() * $modifier);
        }
        if ($buff->getTemplate()->getMpBuff() < 1 && $buff->getTemplate()->getMpBuff() > -1) {
            $this->addMP($buff->getTemplate()->getMpBuff() * $this->maxMP * $modifier);
        } else {
            $this->addMP($buff->getTemplate()->getMpBuff() * $modifier);
        }
        $this->buildBuffList();
    }

    //<editor-fold desc="Setter-Getter" defaultstate="collapsed">

    public function getId() {
        return $this->id;
    }

    public function getAccount(): User {
        return $this->account;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getColoredName() {
        if (count($this->coloredNames) == 0) {
            return null;
        }
        foreach ($this->coloredNames as $name) {
            if ($name->getIsActivated()) {
                return $name;
            }
        }
        return null;
    }

    public function getColoredNames() {
        return $this->coloredNames;
    }

    public function getLevel(): int {
        return $this->level;
    }

    public function getMaxHP(): int {
        return $this->maxHP;
    }

    public function getCurrentHP() {
        return $this->currentHP;
    }

    public function getMaxStamina() {
        return $this->maxStamina;
    }

    public function getCurrentStamina(): int {
        return $this->currentStamina;
    }

    public function getExp() {
        return $this->exp;
    }

    public function getTitle() {
        if (count($this->titles) == 0) {
            return null;
        }
        foreach ($this->titles as $title) {
            if ($title->getIsActivated()) {
                return $title;
            }
        }
        return null;
    }

    public function getTitles() {
        return $this->titles;
    }

    public function getGender() {
        return $this->gender;
    }

    public function getRace(): Race {
        return $this->race;
    }

    public function getWallet(): Wallet {
        return $this->wallet;
    }

    public function getJob() {
        return $this->job;
    }

    public function getOnline() {
        return $this->online;
    }

    public function getSafe() {
        return $this->safe;
    }

    public function getLastActive(): DateTime {
        return $this->lastActive;
    }

    public function getLocation(): LocationEntity {
        return $this->location;
    }

    public function getBiography() {
        return $this->biography;
    }

    public function getWeapon(): Weapon {
        return $this->weapon;
    }

    public function getArmor(): Armor {
        return $this->armor;
    }

    public function getInventory() {
        return $this->inventory;
    }

    public function getMessageSent() {
        return $this->messageSent;
    }

    public function getReceivedMessages() {
        return $this->receivedMessages;
    }

    public function getDead() {
        return $this->dead;
    }

    public function getBan(): DateTime {
        return $this->ban;
    }

    public function getBanReason() {
        return $this->banReason;
    }

    public function getFinishedQuests() {
        return $this->finishedQuests;
    }

    public function getHasPreied() {
        return $this->hasPreied;
    }

    public function getAttributes(): CharacterAttributes {
        return $this->attributes;
    }

    public function getPostCounter() {
        return $this->postCounter;
    }

    public function getRpState() {
        return $this->rpState;
    }

    public function getBuffs() {
        return $this->buffs;
    }

    public function getStaminaHealed() {
        return $this->staminaHealed;
    }

    public function getPartner() {
        return $this->partner;
    }

    public function getDiaryEntries() {
        return $this->diaryEntries;
    }

    public function getKnownRecipes() {
        return $this->knownRecipes;
    }

    public function getGuild() {
        return $this->guild;
    }

    public function getSpells() {
        return $this->spells;
    }

    public function getCurrentMP() {
        return $this->currentMP;
    }

    public function getMaxMP() {
        return $this->maxMP;
    }

    public function setCurrentMP($currentMP) {
        $this->currentMP = $currentMP;
    }

    public function setMaxMP($maxMP) {
        $this->maxMP = $maxMP;
    }

    public function setSpells($spells) {
        $this->spells = $spells;
    }

    public function setGuild($guild) {
        $this->guild = $guild;
    }

    public function setKnownRecipes($knownRecipes) {
        $this->knownRecipes = $knownRecipes;
    }

    public function setDiaryEntries($diaryEntries) {
        $this->diaryEntries = $diaryEntries;
    }

    public function setPartner($partner) {
        $this->partner = $partner;
    }

    public function setStaminaHealed($staminaHealed) {
        $this->staminaHealed = $staminaHealed;
    }

    public function setBuffs($buffs) {
        $this->buffs = $buffs;
    }

    public function setRpState($rpState) {
        $this->rpState = $rpState;
    }

    public function setPostCounter($postCounter) {
        $this->postCounter = $postCounter;
    }

    public function setAttributes(CharacterAttributes $attributes) {
        $this->attributes = $attributes;
    }

    public function setAccount(User $account) {
        $this->account = $account;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setColoredName($coloredName) {
        foreach ($this->coloredNames as $other) {
            $other->setIsActivated($coloredName->getId() == $other->getId());
        }
    }

    public function setLevel($level) {
        $this->level = $level;
    }

    public function setMaxHP($maxHP) {
        $this->maxHP = $maxHP;
    }

    public function setCurrentHP($currentHP) {
        $this->currentHP = $currentHP;
    }

    public function setMaxStamina($maxStamina) {
        $this->maxStamina = $maxStamina;
    }

    public function setCurrentStamina($currentStamina) {
        $this->currentStamina = $currentStamina;
    }

    public function setExp($exp) {
        $this->exp = $exp;
    }

    public function setTitle(Title $title) {
        foreach ($this->titles as $other) {
            $other->setIsActivated($title->getId() == $other->getId());
        }
    }

    public function setGender($gender) {
        $this->gender = $gender;
    }

    public function setRace(Race $race) {
        $this->race = $race;
    }

    public function setWallet(Wallet $wallet) {
        $this->wallet = $wallet;
        $wallet->setOwner($this);
    }

    public function setJob(Job $job) {
        $this->job = $job;
    }

    public function setOnline($online) {
        $this->online = $online;
    }

    public function setSafe($safe) {
        $this->safe = $safe;
    }

    public function setLastActive(DateTime $lastActive) {
        $this->lastActive = $lastActive;
    }

    public function setLocation(LocationEntity $location) {
        $this->location = $location;
    }

    public function setBiography(array $biography) {
        $this->biography = $biography;
    }

    public function setWeapon(Weapon $weapon) {
        $this->weapon = $weapon;
    }

    public function setArmor(Armor $armor) {
        $this->armor = $armor;
    }

    public function setInventory(array $inventory) {
        $this->inventory = $inventory;
    }

    public function setMessageSent(array $messageSent) {
        $this->messageSent = $messageSent;
    }

    public function setReceivedMessages(array $receivedMessages) {
        $this->receivedMessages = $receivedMessages;
    }

    public function setDead($dead) {
        $this->dead = $dead;
    }

    public function setBan(DateTime $ban) {
        $this->ban = $ban;
    }

    public function setBanReason($banReason) {
        $this->banReason = $banReason;
    }

    public function setFinishedQuests($finishedQuests) {
        $this->finishedQuests = $finishedQuests;
    }

    public function setHasPreied($hasPreied) {
        $this->hasPreied = $hasPreied;
    }

    //</editor-fold>
}
