<?php

namespace App\Service\NavbarFactory;

use App\Controller\WorldController;
use App\Service\NavbarService;
use App\Util\TabIdentification\TabIdentifier;

/**
 * Description of BiographyNavbarGenerator
 *
 * @author Draeius
 */
class BiographyNavbarGenerator {

    /**
     *
     * @var NavbarService
     */
    private $navbarService;

    function __construct(NavbarService $navbarService) {
        $this->navbarService = $navbarService;
    }

    public function getStandardNavbar(TabIdentifier $tabIdentifier, Character $char) {
        $this->navbarService->addNav('Zurück', WorldController::STANDARD_WORLD_ROUTE_NAME, [
            'uuid' => $tabIdentifier->getIdentifier(),
            'locationId' => $char->getLocation()->getId()]);
        return $this->navbarService;
    }

}
