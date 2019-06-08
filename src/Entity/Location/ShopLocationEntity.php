<?php

namespace App\Entity\Location;

use AppBundle\Entity\Item;
use AppBundle\Util\Config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;
use App\Entity\Location\LocationEntity;
use NavigationBundle\Location\ShopLocation;

/**
 * Description of ShopLocationEntity
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_shop")
 */
class ShopLocationEntity extends LocationEntity {

    /**
     *
     * @var Item[]
     * @ManyToMany(targetEntity="AppBundle\Entity\Item", fetch="EXTRA_LAZY")
     * @JoinTable(name="shop_items",
     *      joinColumns={@JoinColumn(name="location_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="item_id", referencedColumnName="id")}
     *      )
     */
    protected $items;

    public function getTemplate() {
        return 'locations/shopLocation';
    }

    public function getItems() {
        return $this->items;
    }

    public function setItems($items) {
        $this->items = $items;
    }

    public function addItem(Item $item) {
        $this->items[] = $item;
    }

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new ShopLocation($manager, $uuid, $this, $config);
    }

    public function sellsItem(Item $item) {
        foreach ($this->items as $stock) {
            if ($stock->getId() == $item->getId()) {
                return true;
            }
        }
        return false;
    }

}
