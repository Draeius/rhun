<?php

namespace App\Service;

use App\Entity\Location;
use App\Entity\Navigation;
use App\Repository\LocationRepository;
use App\Repository\NavigationRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of NavigationUpdater
 *
 * @author Draeius
 */
class NavigationUpdater {

    /**
     *
     * @var NavigationRepository
     */
    private $navRepo;

    /**
     *
     * @var LocationRepository
     */
    private $locRep;

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    function __construct(NavigationRepository $navRepo, LocationRepository $locRep, EntityManagerInterface $eManager) {
        $this->navRepo = $navRepo;
        $this->locRep = $locRep;
        $this->eManager = $eManager;
    }

    public function update($locations, $data) {
        foreach ($locations as $loc) {
            $dataEntry = $this->getLocationFromData($loc->getId(), $data);
            if ($dataEntry) {
                $this->checkExistingNavs($loc, $dataEntry);
                $this->checkDataNavs($loc, $dataEntry);
            }
        }
    }

    private function getLocationFromData(int $id, $data) {
        foreach ($data as $entry) {
            if ($entry['id'] == $id) {
                return $entry;
            }
        }
        return false;
    }

    private function checkExistingNavs(Location $location, $data) {
        $navs = $this->navRepo->findByLocation($location);
        foreach ($navs as $nav) {
            $existsInData = false;
            foreach ($data['connections'] as $target) {
                if ($nav->getTargetLocation() && $target == $nav->getTargetLocation()->getId()) {
                    $existsInData = true;
                }
            }
            if (!$existsInData && $nav->getTargetLocation()->getArea()->getId() == $location->getArea()->getId()) {
                $this->eManager->remove($nav);
            }
        }
    }

    private function checkDataNavs(Location $location, $data) {
        $navs = $this->navRepo->findByLocation($location);
        foreach ($data['connections'] as $target) {
            $existsInLocation = false;
            foreach ($navs as $nav) {
                if ($nav->getTargetLocation() && $target == $nav->getTargetLocation()->getId()) {
                    $existsInLocation = true;
                }
            }
            if (!$existsInLocation) {
                $targetEntity = $this->locRep->find($target);
                $navigation = new Navigation();
                $navigation->setName($targetEntity->getName());
                $navigation->setColoredName($targetEntity->getColoredName());
                $navigation->setLocation($location);
                $navigation->setTargetLocation($targetEntity);

                $this->eManager->persist($navigation);
                $this->eManager->persist($location);
            }
        }
    }

}
