<?php

namespace App\Util\Config;

/**
 * Description of GuildConfig
 *
 * @author Draeius
 */
class GuildConfig extends Config {

    public function getGuildFoundingPrice(): Price {
        return $this->getPrice('founding_price');
    }

}
