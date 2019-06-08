<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity\Location;

use AppBundle\Util\Config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use NavigationBundle\Location\GuildListLocation;

/**
 * Description of GuildLocationEntity
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_guild_lists")
 */
class GuildListLocationEntity extends LocationEntity {

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new GuildListLocation($manager, $uuid, $this, $config);
    }

    public function getTemplate() {
        return 'locations/guildListLocation';
    }

}
