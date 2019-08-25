<?php

namespace App\Util\Config;

use App\Util\Price;

/**
 * Description of HouseConfig
 *
 * @author Draeius
 */
class HouseConfig extends Config {

    public function getHouseBuildPrice(): Price {
        return $this->getPrice('price');
    }

    public function getRoomBuildPrice(): Price {
        $data = $this->getConfigData('room');
        return $this->getPrice('price', $data);
    }

    public function getHouseLevels(): array {
        return $this->getConfigData('levels');
    }

    public function getIntrusionChance(): int {
        return $this->getConfigData('intrusion_chance');
    }

    public function getHouseAreaId(): int {
        return $this->getData('house_area');
    }

    public function getNavUpdatePrice(): Price {
        $data = $this->getConfigData('nav_update');
        return $this->getPrice('price', $data);
    }

    public function getKeyPrice(): Price {
        $data = $this->getConfigData('key');
        return $this->getPrice('price', $data);
    }
    
    public function getFreeKeyCount(): int {
        return $this->getConfigData('key')['key_per_room'];
    }
    
    public function getMaxRooms(): int {
        return $this->getData('max_rooms');
    }

}
