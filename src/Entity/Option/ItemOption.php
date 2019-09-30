<?php

namespace App\Entity\Option;

use App\Entity\Character;
use App\Entity\Items\Item;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * 
 * @Entity
 * @Table(name="options_rec_item")
 */
class ItemOption extends Option {

    /**
     *
     * @var Item
     * @ManyToOne(targetEntity="App\Entity\Items\Item")
     * @JoinColumn(name="item_id", referencedColumnName="id")
     */
    private $item;

    /**
     * @var int
     * @Column(type="integer")
     */
    private $amount;

    public function execute(EntityManagerInterface $eManager, Character $character) {
        if ($this->amount > 0) {
            $invItem = $character->addToInventory($this->item, $this->amount);
        } else {
            $invItem = $character->removeFromInventory($this->item, $this->amount);
        }
        $eManager->persist($invItem);
        $eManager->flush();
    }

}
