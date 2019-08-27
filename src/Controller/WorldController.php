<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Entity\Character;
use App\Entity\Location;
use App\Repository\CharacterRepository;
use App\Repository\NavigationRepository;
use App\Service\LocationResolveService;
use App\Service\NavbarFactory\WorldNavbarFactory;
use App\Service\ParamGenerator\WorldParamGenerator;
use App\Service\SkinService;
use App\Util\Session\RhunSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of WorldController
 *
 * @author Draeius
 * @Route("/world")
 * App\Annotation\Security(needCharacter=true, needAccount=true)
 */
class WorldController extends BasicController {

    const STANDARD_WORLD_ROUTE_NAME = 'world';

    /**
     *
     * @var WorldNavbarFactory
     */
    private $worldNavFactory;

    /**
     *
     * @var type WorldParamGenerator
     */
    private $paramGenerator;

    function __construct(SkinService $skinService, WorldNavbarFactory $worldNavFactory, WorldParamGenerator $generator) {
        parent::__construct($skinService);
        $this->worldNavFactory = $worldNavFactory;
        $this->paramGenerator = $generator;
    }

    /**
     * @Route("/location/{uuid}/{locationId}", name=WorldController::STANDARD_WORLD_ROUTE_NAME)
     */
    public function showLocation(string $locationId, CharacterRepository $charRepo, EntityManagerInterface $eManager, LocationResolveService $resolver,
            NavigationRepository $navRepo) {
        $session = new RhunSession();
        $character = $charRepo->find($session->getCharacterID());
        if (!$character) {
            $session->deleteCharacterID();
            return $this->redirectToRoute(AccountManagementController::ACCOUNT_MANAGEMENT_ROUTE_NAME);
        }

        $location = $resolver->getLocation($character, $locationId);
        $this->persistLocation($eManager, $character, $location);
        return $this->render($this->getSkinFile(), $this->paramGenerator->getWorldParams($location, $character, $navRepo));
    }

    private function persistLocation(EntityManagerInterface $manager, Character &$character, Location $location) {
        $character->setLocation($location);
        $manager->flush($character);
    }

}
