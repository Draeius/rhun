<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service\NavbarFactory;

use App\Controller\WorldController;
use App\Doctrine\UuidEncoder;
use App\Entity\Character;
use App\Entity\Location;
use App\Entity\LocationBase;
use App\Entity\Navigation;
use App\Repository\NavigationRepository;
use App\Service\ConfigService;
use App\Service\DateTimeService;
use App\Service\LocationResolveService;
use App\Service\NavbarFactory\Location\NavbarModifierBase;
use App\Service\NavbarService;
use App\Util\Session\RhunSession;
use App\Util\TabIdentification\TabIdentifier;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * Description of WorldNavbarFactory
 *
 * @author Draeius
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

    /**
     *
     * @var DateTimeService
     */
    private $dtService;

    /**
     *
     * @var ConfigService;
     */
    private $config;

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    function __construct(EntityManagerInterface $eManager, NavbarService $navbarService, DateTimeService $dtService,
            LocationResolveService $locResolveService, ConfigService $config) {
        $this->eManager = $eManager;
        $this->navbarService = $navbarService;
        $this->locResolveService = $locResolveService;
        $this->dtService = $dtService;
        $this->config = $config;
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
        $encoder = new UuidEncoder();
        if ($nav->getTargetLocation() != NULL) {
            $this->navbarService->addNav($nav->getColoredName(), WorldController::STANDARD_WORLD_ROUTE_NAME,
                    ['uuid' => $tabIdentifier->getIdentifier(), 'locationId' => $encoder->encode($nav->getTargetLocation()->getUuid())]);
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
        $generator = new $class($character, $this->dtService, $this->eManager, $this->config);
        if (!$generator instanceof NavbarModifierBase) {
            throw new Exception($class . ' is no valid ParamGenerator. Must be subclass of LocationParamGenerator');
        }
        return $generator;
    }

}
