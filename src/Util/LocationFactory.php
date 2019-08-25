<?php

namespace App\Util;

use App\Entity\Area;
use App\Entity\Location;

/**
 * Description of LocationFactory
 *
 * @author Draeius
 */
class LocationFactory {

    public static function createNewBedroom($title, Area &$area, $descrSpring, $descrSummer, $descrFall, $descrWinter, $adult): Location {
        $bedroom = new Location();
        self::assignVariables($bedroom, $title, $area, $descrSpring, $descrSummer, $descrFall, $descrWinter, $adult);
        $bedroom->setBedroom(true);
        return $bedroom;
    }

    public static function createNewWorkroom($title, Area &$area, $descrSpring, $descrSummer, $descrFall, $descrWinter, $adult): Location {
        $workroom = new Location();
        self::assignVariables($workroom, $title, $area, $descrSpring, $descrSummer, $descrFall, $descrWinter, $adult);
        $workroom->setWorkroom(true);
        return $workroom;
    }

    public static function createNewEmptyRoom($title, Area &$area, $descrSpring, $descrSummer, $descrFall, $descrWinter, $adult): Location {
        $room = new Location();
        self::assignVariables($room, $title, $area, $descrSpring, $descrSummer, $descrFall, $descrWinter, $adult);
        return $room;
    }

    public static function createNewKitchen($title, Area &$area, $descrSpring, $descrSummer, $descrFall, $descrWinter, $adult): Location {
        $kitchen = new Location();
        self::assignVariables($kitchen, $title, $area, $descrSpring, $descrSummer, $descrFall, $descrWinter, $adult);
        $kitchen->setCrafting(true);
        return $kitchen;
    }

    private static function assignVariables(Location &$location, $title, Area &$area, $descrSpring, $descrSummer, $descrFall, $descrWinter, $adult): void {
        $location->setColoredName($title);
        $location->setName($title);
        $location->setArea($area);
        $location->setDescriptionSpring($descrSpring);
        $location->setDescriptionSummer($descrSummer);
        $location->setDescriptionFall($descrFall);
        $location->setDescriptionWinter($descrWinter);
        $location->setPost(true);
        $location->setAdult($adult);
    }

}
