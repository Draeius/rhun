<?php

namespace App\Service\ParamGenerator\Location;

use App\Entity\LocationBase;
use App\Repository\AreaRepository;
use App\Repository\NavigationRepository;
use App\Util\Data2TreeExporter;

/**
 * Description of GuildAdminGenerator
 *
 * @author Draeius
 */
class GuildAdminGenerator extends LocationParamGeneratorBase {

    public function getParams(LocationBase $location): array {
        /* @var $areaRepo AreaRepository */
        $areaRepo = $this->getEntityManager()->getRepository('App:Area');
        /* @var $navRepo NavigationRepository */
        $navRepo = $this->getEntityManager()->getRepository('App:Navigation');

        $guild = $this->getCharacter()->getGuild();
        $navigations = $navRepo->findByGuild($guild);

        return [
            'guild' => $guild,
            'cities' => $areaRepo->getAreasAllowingRaces(),
            'locData' => Data2TreeExporter::exportLocationData($guild->getGuildHall()->getLocations()),
            'navData' => Data2TreeExporter::exportNavData($navigations),
            'locations' => $guild->getGuildHall()->getLocations(),
            'invitations' => $this->getEntityManager()->getRepository('App:GuildInvitation')->findByGuild($this->getCharacter()->getGuild())
        ];
    }

}
