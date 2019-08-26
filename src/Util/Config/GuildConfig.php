<?php

namespace App\Util\Config;

use App\Util\Price;

/**
 * Description of GuildConfig
 *
 * @author Draeius
 */
class GuildConfig extends Config {

    public function getGuildFoundingPrice(): Price {
        return $this->getPrice('founding_price');
    }

    public function getAddRoomBasePrice(): Price {
        $data = $this->getConfigData('projects');
        return $this->getPrice('base_price', $data['add_room']);
    }

    public function getAddRoomPriceAddition(): Price {
        $data = $this->getConfigData('projects');
        return $this->getPrice('size_addition', $data['add_room']);
    }

    public function getAddTrainingPrice(): Price {
        $data = $this->getConfigData('projects');
        return $this->getPrice('price', $data['training']);
    }

    public function getChangeTrainingPrice(): Price {
        $data = $this->getConfigData('buffed_attribute');
        return $this->getPrice('change_price', $data);
    }

    public function getNavUpdatePrice(): Price {
        $data = $this->getConfigData('nav_update');
        return $this->getPrice('price', $data);
    }

}
