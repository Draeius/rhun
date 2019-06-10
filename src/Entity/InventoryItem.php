<?php

namespace App\Entity;

use App\Entity\Character;
use App\Entity\Items\Item;
use App\Entity\Traits\EntityOwnerTrait;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * A players inventory
 *
 * @author Matthias
 * @Entity
 * @Table(name="inventory_items")
 */
class InventoryItem extends RhunEntity {

    use EntityOwnerTrait;

    /**
     *
     * @var Character
     * @ManyToOne(targetEntity="Character", inversedBy="inventory")
     */
    protected $owner;

    /**
     * The item that is wrapped by this InventoryItem
     * @var Item 
     * @ManyToOne(targetEntity="App\Entity\Items\Item")
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

    public function getItem() {
        return $this->item;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function setItem(Item $item) {
        $this->item = $item;
    }

    public function setAmount($amount) {
        $this->amount = $amount;
    }

}
