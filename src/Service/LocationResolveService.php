<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\Location;
use App\Entity\LocationBase;
use App\Repository\LocationRepository;

/**
 * Description of LocationResolveService
 *
 * @author Draeius
 */
class LocationResolveService {

    /**
     *
     * @var LocationRepository
     */
    private $locRepo;

    function __construct(LocationRepository $locRepo) {
        $this->locRepo = $locRepo;
    }

    public function getLocation(Character $character, $locationId): LocationBase {
        $location = $this->locRepo->find($locationId);

        if (!$this->isValidLocation($location, $character)) {
            return $character->getLocation();
        }
//        
//        if ($location instanceof HousingLocationEntity) {
//            $session = new RhunSession();
//            $session->setBreakIn(false);
//        }
//        $roomArray = $this->getDoctrine()->getRepository('App:Room')->findByLocation($location);
//        if ($roomArray) {
//            $house = $roomArray[0]->getHouse();
//            if (!HouseService::characterMayEnter($house, $character)) {
//                return $house->getLocation();
//            }
//        }
        return $location;
    }

    public function isValidLocation(?Location $location, Character $character) {
        if (!$location) {
            return false;
        }
        if ($location->getId() <= 1) {
            //Location is OOC Post location
            return false;
        }
        if (!$character->getAccount()->isAdult() && $location->getAdult()) {
            return false;
        }
        if ($character->getDead()) {
            return $location->getArea()->getDeadAllowed();
        }
        foreach ($character->getRace()->getAllowedAreas() as $area) {
            if ($area->getId() == $location->getArea()->getId()) {
                return true;
            }
        }
        //todo: return false
        return true;
    }

}
