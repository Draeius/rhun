<?php

namespace App\Service\ParamGenerator\Location;

use App\Entity\House;
use App\Entity\LocationBase;
use App\Repository\AreaRepository;
use App\Repository\HouseRepository;
use App\Repository\NavigationRepository;
use App\Util\Data2TreeExporter;
use App\Service\HouseService;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Description of WorkroomGenerator
 *
 * @author Draeius
 */
class WorkroomGenerator extends LocationParamGeneratorBase {

    public function getParams(LocationBase $location): array {

        /* @var $areaRepo AreaRepository */
        $areaRepo = $this->getEntityManager()->getRepository('App:Area');
        /* @var $houseRep HouseRepository */
        $houseRep = $this->getEntityManager()->getRepository('App:House');
        /* @var $navRepo NavigationRepository */
        $navRepo = $this->getEntityManager()->getRepository('App:Navigation');
        /* @var $house House */
        $house = $houseRep->findByLocation($location);

        $navigations = $navRepo->findByHouse($house);

        return [
//            'location' => $this->getLocation()->getId(),
            'house' => $house,
            'rooms' => $house->getRooms(),
            'locData' => Data2TreeExporter::exportLocationData($house->getRooms()),
            'navData' => Data2TreeExporter::exportNavData($navigations),
            'cities' => $areaRepo->getAreasAllowingRaces(),
            'addInhabPrice' => HouseService::getPriceKey($house, $this->getConfig()->getHouseConfig()),
            'buyRoomPrice' => HouseService::getBuyRoomPrice($house, $this->getConfig()->getHouseConfig())
        ];
    }

}
