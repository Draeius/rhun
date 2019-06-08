<?php

namespace App\Entity\Location;

use AppBundle\Util\Config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use App\Entity\Location\LocationEntity;
use NavigationBundle\Location\GuildAdministrationLocation;

/**
 * Description of GuildAdministrationLocationEntity
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_guild_administrations")
 */
class GuildAdministrationLocationEntity extends LocationEntity {

    public function getTemplate() {
        return 'locations/guildAdministrationLocation';
    }

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new GuildAdministrationLocation($manager, $uuid, $this, $config);
    }

}
