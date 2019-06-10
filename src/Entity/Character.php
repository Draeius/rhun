<?php

namespace App\Entity;

use App\Entity\Fauna\FighterCharacter;
use App\Entity\Traits\CharacterInventoryTrait;
use App\Entity\Traits\EntityLevelTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of Character
 *
 * @author Matthias
 * @Entity(repositoryClass="App\Repository\CharacterRepository")
 * @Table(name="characters")
 */
class Character extends FighterCharacter {

    use EntityLevelTrait;
    use CharacterInventoryTrait;

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
     * If this character is safe from pvp
     * @var bool
     * @Column(type="boolean")
     */
    protected $safe = true;

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
    protected $staminaHealed = 0;

    /**
     * @var Character
     * @OneToOne(targetEntity="Character", fetch="EXTRA_LAZY")
     * @JoinColumn(name="partner_id", referencedColumnName="id")
     */
    protected $partner = null;

    /**
     * @var CraftRecipe[]
     * @ManyToMany(targetEntity="CraftRecipe", fetch="EXTRA_LAZY")
     * @JoinTable(name="character_known_recipes",
     *      joinColumns={@JoinColumn(name="character_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="recipe_id", referencedColumnName="id")}
     *      )
     */
    protected $knownRecipes;

    public function __construct() {
        parent::__construct();
        $this->inventory = new ArrayCollection();
        $this->finishedQuests = new ArrayCollection();
        $this->wallet = new Wallet();
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

    public function levelUp(): void {
        //increment level
        $this->level ++;
        $this->maxHP = 50 + 50 * $this->level;
        $this->currentHP = $this->getMaxHPWithBuff();

        $this->s($this->attributes->getSkillPoints() + 6);
    }

}
