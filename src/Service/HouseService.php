<?php

namespace App\Service;

use App\Entity\Area;
use App\Entity\Character;
use App\Entity\House;
use App\Entity\Location;
use App\Entity\Navigation;
use App\Util\Config\HouseConfig;
use App\Util\Price;
use App\Util\Session\RhunSession;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of HouseService
 *
 * @author Draeius
 */
class HouseService {

    public static function getHouseLevel(int $numberRooms, HouseConfig $config) {
        $levels = $config->getHouseLevels();
        while (!array_key_exists($numberRooms, $levels)) {
            $numberRooms--;
        }
        return $levels[$numberRooms];
    }

    /**
     * Gets the price for building a new Room in a house
     * @return Price
     */
    public static function getBuyRoomPrice(House $house, HouseConfig $config) {
        $roomCount = count($house->getRooms());

        if ($roomCount >= $config->getMaxRooms() ||
                self::getHouseLevel($roomCount, $config) != self::getHouseLevel($roomCount + 1, $config)) {
            return $config->getHouseBuildPrice();
        }
        return $config->getRoomBuildPrice();
    }

    public static function getPriceKey(House $house, HouseConfig $config) {
        $rooms = count($house->getRooms());
        $inhabs = count($house->getInhabitants());
        $price = $config->getKeyPrice();

        $gold = $price->getGold() * $inhabs - ($price->getGold() * $rooms * $config->getFreeKeyCount());
        $platin = $price->getPlatin() * $inhabs - ($price->getPlatin() * $rooms * $config->getFreeKeyCount());
        $gems = $price->getGems() * $inhabs - ($price->getGems() * $rooms * $config->getFreeKeyCount());
        if ($gold < 0) {
            $gold = 0;
        }
        if ($platin < 0) {
            $platin = 0;
        }
        if ($gems < 0) {
            $gems = 0;
        }

        return new Price($gold, $platin, $gems);
    }

    /**
     * Creates a new House with standard rooms and locations.
     * @param string $title
     * @param string $description
     * @param LocationEntity $location
     * @param Character $owner
     * @return House
     */
    public function createNewHouse($title, $description, Location $location, Character $owner, Area $area): House {
        $house = new House();
        $house->setTitle($title);
        $house->setDescription($description);
        $house->setLocation($location);
        $house->setOwner($owner);
        $house->setShowOwner(FALSE);

        $roomService = new RoomService();
        $hallway = $roomService->createNewEmptyRoom($house, $area, 'Flur', NULL, NULL, NULL);
        $roomService->createNewBedroom($house, $area, 'Ein Schlafzimmer', NULL, NULL, NULL);
        $roomService->createNewWorkroom($house, $area, 'Dein Arbeitszimmer', NULL, NULL, NULL);
        $house->setEntrance($hallway);
        return $house;
    }

    /**
     * Increments the max amount of rooms
     * @param House $house
     */
    public function buyRoom(House &$house, $type) {
        $house->setMaxRooms($house->getMaxRooms() + 1);
        $roomService = new RoomService();
        switch ($type) {
            case RoomService::TYPE_BATHROOM:
                break;
            case RoomService::TYPE_KITCHEN:
                $room = $roomService->createNewKitchen($house, $house->getLocation()->getArea(), 'Deine Küche', NULL, NULL, NULL);
                $house->addRoom($room);
                break;
            case RoomService::TYPE_STORAGE:
                $room = $roomService->createNewStorage($house, $house->getLocation()->getArea(), 'Deine Küche', NULL, NULL, NULL);
                break;
            case RoomService::TYPE_EMPTY:
                $room = $roomService->createNewEmptyRoom($house, $house->getLocation()->getArea(), 'Leerer Raum', NULL, NULL, NULL);
                $house->addRoom($room);
                break;
            case RoomService::TYPE_CS:
                $room = $roomService->createNewEmptyRoom($house, $house->getLocation()->getArea(), 'Leerer CS Raum', NULL, NULL, NULL);
                $room->getLocation()->setAdult(true);
                $house->addRoom($room);
                break;
        }
    }

    public static function characterMayEnter(House $house, Character $character) {
        if ($house->getOwner()->getId() == $character->getId()) {
            return true;
        }
        foreach ($house->getInhabitants() as $inhabitant) {
            if ($inhabitant->getId() == $character->getId()) {
                return true;
            }
        }
        $session = new RhunSession();
        if ($session->getBreakIn()) {
            return true;
        }
        return false;
    }

    public static function checkPrice(Price $price, House $house, Character $character) {
        if ($price->getGold() > $character->getWallet()->getGold()) {
            return false;
        }
        if ($price->getPlatin() > $character->getWallet()->getPlatin() + $house->getHouseFunctions()->getSavingsPlatin()) {
            return false;
        }
        if ($price->getPlatin() > $character->getWallet()->getGems() + $house->getHouseFunctions()->getSavingsGems()) {
            return false;
        }
        return true;
    }

    public static function payPrice(Price $price, House &$house, Character &$character) {
        if (self::checkPrice($price, $house, $character)) {
            $houseFunctions = $house->getHouseFunctions();
            $character->getWallet()->addGold(-1 * $price->getGold());
            $houseFunctions->setSavingsPlatin($houseFunctions->getSavingsPlatin() - $price->getPlatin());
            $houseFunctions->setSavingsGems($houseFunctions->getSavingsGems() - $price->getGems());
            if ($houseFunctions->getSavingsPlatin() < 0) {
                $character->getWallet()->addPlatin($houseFunctions->getSavingsPlatin());
                $houseFunctions->setSavingsPlatin(0);
            }
            if ($houseFunctions->getSavingsGems() < 0) {
                $character->getWallet()->addGems($houseFunctions->getSavingsGems());
                $houseFunctions->setSavingsGems(0);
            }
            return true;
        }
        return false;
    }

    public function createHouseNavs(House $house, EntityManagerInterface &$eManager) {
        $leave = new Navigation();
        $leave->setLocation($house->getEntrance());
        $leave->setTargetLocation($house->getLocation());
        $leave->setName('Haus verlassen');
        $leave->setColoredName('`QHaus verlassen');
        $leave->setNavbarIndex(-1);
        $eManager->persist($leave);
        $eManager->flush();

        /* @var $room Location */
        foreach ($house->getRooms() as $room) {
            if ($room->getId() != $house->getEntrance()->getId()) {
                $to = new Navigation();
                $to->setLocation($house->getEntrance());
                $to->setTargetLocation($room);
                $to->setName($room->getName());
                $to->setColoredName($room->getColoredName());

                $from = new Navigation();
                $from->setLocation($room);
                $from->setTargetLocation($house->getEntrance());
                $from->setName($house->getEntrance()->getName());
                $from->setColoredName($house->getEntrance()->getColoredName());

                $eManager->persist($to);
                $eManager->persist($from);
                $eManager->flush();
            }
        }
    }

}
