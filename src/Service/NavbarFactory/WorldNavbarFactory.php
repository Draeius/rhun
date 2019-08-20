<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service\NavbarFactory;

use App\Controller\WorldController;
use App\Entity\Character;
use App\Entity\Location;
use App\Entity\LocationBase;
use App\Entity\Navigation;
use App\Repository\NavigationRepository;
use App\Service\LocationResolveService;
use App\Service\NavbarFactory\Location\NavbarModifierBase;
use App\Service\NavbarService;
use App\Util\Session\RhunSession;
use App\Util\TabIdentification\TabIdentifier;
use Exception;

/**
 * Description of WorldNavbarFactory
 *
 * @author Matthias
 */
class WorldNavbarFactory {

    /**
     *
     * @var NavbarService
     */
    private $navbarService;

    /**
     *
     * @var LocationResolveService
     */
    private $locResolveService;

    function __construct(NavbarService $navbarService, LocationResolveService $locResolveService) {
        $this->navbarService = $navbarService;
        $this->locResolveService = $locResolveService;
    }

    public function buildNavbar(LocationBase $location, Character $character, NavigationRepository $navRepo) {
        $session = new RhunSession();
        $this->buildStandardNavbar($location, $character, $navRepo);
//        $this->navbarService->addNavhead('Karte')
//                ->addNav('Karte', 'map', ['uuid' => $session->getTabIdentifier()->getIdentifier()]);
        return $this->navbarService;
    }

    public function buildStandardNavbar(Location $location, Character $character, NavigationRepository $navRepo) {
        $navs = $navRepo->findByLocation($location);
        $session = new RhunSession();
        foreach ($navs as $entry) {
            if ($entry->getTargetLocation() === null || $this->locResolveService->isValidLocation($entry->getTargetLocation(), $character)) {
                $this->addNav($entry, $session->getTabIdentifier());
            }
        }

        $addIns = $location->getDataArray();
        foreach ($addIns as $key => $active) {
            if ($active && $modifier = $this->createNavbarModifier($key, $character)) {
                $modifier->modifyNavbar($this->navbarService, $location);
            }
        }
    }

    private function addNav(Navigation $nav, TabIdentifier $tabIdentifier) {
        if ($nav->getHref()) {
            if ($nav->getPopup()) {
                $this->navbarService->addPopupLink($nav->getColoredName(), $nav->getHref());
            } else {
                $this->navbarService->addLink($nav->getColoredName(), $nav->getHref());
            }
            return;
        }
        if ($nav->getTargetLocation() != NULL) {
            $this->navbarService->addNav($nav->getColoredName(), WorldController::STANDARD_WORLD_ROUTE_NAME,
                    ['uuid' => $tabIdentifier->getIdentifier(), 'locationId' => $nav->getTargetLocation()->getId()]);
            return;
        }
        $this->navbarService->addNavhead($nav->getColoredName());
    }

    private function getNavbarModifierClass(string $index) {
        return 'App\\Service\\NavbarFactory\\Location\\' . ucfirst($index) . 'Modifier';
    }

    private function createNavbarModifier(string $index, Character $character): ?NavbarModifierBase {
        $class = $this->getNavbarModifierClass($index);
        if (!class_exists($class)) {
            return null;
        }
        $generator = new $class($character, $this->getDtService(), $this->eManager, $this->config);
        if (!$generator instanceof NavbarModifierBase) {
            throw new Exception($class . ' is no valid ParamGenerator. Must be subclass of LocationParamGenerator');
        }
        return $generator;
    }

}
