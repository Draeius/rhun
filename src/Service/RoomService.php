<?php

namespace App\Service;

use App\Entity\Area;
use App\Entity\House;
use App\Entity\Location;
use App\Util\LocationFactory;

/**
 * Description of RoomService
 *
 * @author Draeius
 */
class RoomService {

    const TYPE_BEDROOM = 'Schlafzimmer';
    const TYPE_WORKROOM = 'Arbeitszimmer';
    const TYPE_KITCHEN = 'Küche';
    const TYPE_BATHROOM = 'Badezimmer';
    const TYPE_STORAGE = 'Lagerraum';
    const TYPE_EMPTY = 'Leer';
    const TYPE_CS = 'CS Raum';

    public function createNewBedroom(House &$house, &$area, $descrSpring, $descrSummer, $descrFall, $descrWinter): Location {
        $location = LocationFactory::createNewBedroom(self::TYPE_BEDROOM, $area
                        , $descrSpring, $descrSummer, $descrFall, $descrWinter, false);

        $house->addRoom($location);
        return $location;
    }

    public function createNewWorkroom(House &$house, Area &$area, $descrSpring, $descrSummer, $descrFall, $descrWinter): Location {
        $location = LocationFactory::createNewWorkroom(self::TYPE_WORKROOM, $area
                        , $descrSpring, $descrSummer, $descrFall, $descrWinter, false);

        $house->addRoom($location);
        return $location;
    }

    public function createNewEmptyRoom(House &$house, Area &$area, $descrSpring, $descrSummer, $descrFall, $descrWinter): Location {
        $location = LocationFactory::createNewEmptyRoom(self::TYPE_EMPTY, $area
                        , $descrSpring, $descrSummer, $descrFall, $descrWinter, false);

        $house->addRoom($location);
        return $location;
    }

    public function createNewCSRoom(House &$house, Area &$area, $descrSpring, $descrSummer, $descrFall, $descrWinter): Location {
        $location = $this->createNewEmptyRoom($house, $area, $descrSpring, $descrSummer, $descrFall, $descrWinter);
        $location->setCs(true);
        return $location;
    }

    public function createNewKitchen(House &$house, Area &$area, $descrSpring, $descrSummer, $descrFall, $descrWinter): Location {
        $location = LocationFactory::createNewKitchen(self::TYPE_KITCHEN, $area
                        , $descrSpring, $descrSummer, $descrFall, $descrWinter, false);

        $house->addRoom($location);
        return $location;
    }

    public function createNewStorage(House &$house, Area &$area, $descrSpring, $descrSummer, $descrFall, $descrWinter): Location {
        $location = LocationFactory::createNewEmptyRoom(self::TYPE_STORAGE, $area
                        , $descrSpring, $descrSummer, $descrFall, $descrWinter, false);

        $location->setStorage(true);
        $house->addRoom($location);
        return $location;
    }

}
