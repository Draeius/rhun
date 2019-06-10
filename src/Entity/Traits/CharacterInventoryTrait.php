<?php

namespace App\Entity\Traits;

use App\Entity\InventoryItem;
use App\Entity\Item;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Description of CharacterInventoryTrait
 *
 * @author Draeius
 */
trait CharacterInventoryTrait {

    /**
     * The character's inventory
     * @var InventoryItem[] 
     * @OneToMany(targetEntity="InventoryItem", mappedBy="owner", cascade={"remove", "persist"}, fetch="EXTRA_LAZY")
     */
    protected $inventory;

    public function getInventoryItemStock(Item $item) {
        $invItem = &$this->findItemInInventory($item);
        if ($invItem) {
            return $invItem->getAmount();
        }
        return 0;
    }

    public function addToInventory(Item $item, $amount) {
        $invItem = &$this->findItemInInventory($item);
        if ($invItem) {
            $invItem->addItem($amount);
            return;
        }
//        //check inventory for items like this one
//        foreach ($this->inventory as $invItem) {
//            //already have some of this item
//            if ($item->getId() == $invItem->getItem()->getId()) {
//                //add one
//                $invItem->addItem($amount);
//                return $invItem;
//            }
//        }

        $toAdd = new InventoryItem();
        $toAdd->setAmount($amount);
        $toAdd->setItem($item);
        $toAdd->setOwner($this);

        //Add to inventory
        $this->inventory[] = $toAdd;
        return $toAdd;
    }

    public function removeFromInventory(Item $item, $amount): void {
        if (!$item || !$amount) {
            return;
        }
        $invItem = &$this->findItemInInventory($item);
        if ($invItem) {
            $invItem->addItem(-1 * $amount);
//            return $invItem;
        }
//        foreach ($this->inventory as $invItem) {
//            //already have some of this item
//            if ($item->getId() == $invItem->getItem()->getId()) {
//                //add one
//                $invItem->addItem(-1 * $amount);
//                return $invItem;
//            }
//        }
    }

    public function hasItem(Item $item) {
        return $this->findItemInInventory($item) != null;
    }

    private function &findItemInInventory(Item $item): ?InventoryItem {
        if (!$item) {
            return null;
        }
        foreach ($this->inventory as &$invItem) {
            if ($item->getId() == $invItem->getItem()->getId()) {
                return $invItem;
            }
        }
        return null;
    }

}
