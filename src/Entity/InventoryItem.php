<?php

namespace App\Entity;

use App\Entity\Character;
use App\Entity\Item;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * A players inventory
 *
 * @author Matthias
 * @Entity
 * @Table(name="inventory_item")
 */
class InventoryItem {

    /**
     * The inventorie's id
     * @var int 
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * The inventories owner
     * @var Character 
     * @ManyToOne(targetEntity="Character", inversedBy="inventory", cascade={"persist"})
     * @JoinColumn(name="character_id", referencedColumnName="id")
     */
    protected $owner;

    /**
     * The item that is wrapped by this InventoryItem
     * @var Item 
     * @ManyToOne(targetEntity="Item", cascade={"persist"})
     * @JoinColumn(name="item_id", referencedColumnName="id")
     */
    protected $item;

    /**
     * How many of the wrapped items the owner has 
     * @var int 
     * @Column(type="integer")
     */
    protected $amount;

    public function addItem($amount) {
        $this->amount += $amount;
        if ($this->amount < 0) {
            $this->amount = 0;
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getOwner() {
        return $this->owner;
    }

    public function getItem() {
        return $this->item;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function setOwner(Character $owner) {
        $this->owner = $owner;
    }

    public function setItem(Item $item) {
        $this->item = $item;
    }

    public function setAmount($amount) {
        $this->amount = $amount;
    }

}
