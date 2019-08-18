<?php

namespace App\Entity\Inventory;

use App\Entity\RhunEntity;
use App\Entity\Traits\EntityOwnerTrait;

/**
 * Eine Klasse, die das Inventar beinhaltet.
 *
 * @author Draeius
 */
class Inventory extends RhunEntity {

    use EntityOwnerTrait;

    /**
     *
     * @var int
     * @Column(type="integer", name="inventory_size")
     */
    protected $size = 50;

    /**
     *
     * @var integer
     * @Column(type="integer")
     */
    protected $itemCount = 0;

    /**
     * The character's inventory
     * @var InventoryItem[] 
     * @OneToMany(targetEntity="App\Entity\Items\Armors", mappedBy="owner", cascade={"remove", "persist"}, fetch="EXTRA_LAZY")
     */
    protected $armors;
    protected $potions;
    protected $savingsBoxes;
    protected $titleStorage;
    protected $weapons;

}
