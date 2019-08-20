<?php

namespace App\Entity;

use App\Entity\Character;
use App\Entity\Item;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of CraftRecipe
 *
 * @author Draeius
 * @Entity(repositoryClass="App\Repository\RecipeRepository")
 * @Table(name="craft_recipes")
 */
class CraftRecipe extends RhunEntity {

    /**
     * The items this recipe needs to be crafted
     * @var Item[]
     * @ManyToMany(targetEntity="App\Entity\Items\Item", fetch="EXTRA_LAZY")
     * @JoinTable(name="ingredients",
     *      joinColumns={@JoinColumn(name="recipe_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="item_id", referencedColumnName="id")}
     *      )
     */
    protected $ingredients;

    /**
     * @var Item
     * @ManyToOne(targetEntity="App\Entity\Items\Item")
     * @JoinColumn(name="result_id", referencedColumnName="id")
     */
    protected $result;

    /**
     * 
     * @var int gems stored on this account 
     * @Column(type="integer")
     */
    protected $successChance;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $minIntelligence = 1;

    public function __construct() {
        $this->ingredients = new ArrayCollection();
    }

    public function craft(EntityManager $manager, Character &$character, $chanceModifier = 0) {
        foreach ($this->ingredients as $item) {
            /* @var $removed InventoryItem */
            $removed = $character->removeFromInventory($item, 1);
            if ($removed->getAmount() == 0) {
                $manager->remove($removed);
            } else {
                $manager->persist($removed);
            }
        }
        if (mt_rand(0, 100) <= $this->successChance + $chanceModifier) {
            $manager->persist($character->addToInventory($this->result, 1));
            $manager->flush();
            return true;
        }
        $manager->flush();
        return false;
    }

    public function hasIngredient(Item $item) {
        foreach ($this->ingredients as $check) {
            if ($check->getId() == $item->getId()) {
                return true;
            }
        }
        return false;
    }

    public function getIngredients() {
        return $this->ingredients;
    }

    public function getResult() {
        return $this->result;
    }

    public function getSuccessChance() {
        return $this->successChance;
    }

    public function getMinIntelligence() {
        return $this->minIntelligence;
    }

    public function setMinIntelligence($minIntelligence) {
        $this->minIntelligence = $minIntelligence;
    }

    public function setIngredients(array $ingredients) {
        $this->ingredients = $ingredients;
    }

    public function setResult(Item $result) {
        $this->result = $result;
    }

    public function setSuccessChance($successChance) {
        $this->successChance = $successChance;
    }

}
