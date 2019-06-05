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

}
