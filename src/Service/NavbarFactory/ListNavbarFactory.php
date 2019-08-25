<?php

namespace App\Service\NavbarFactory;

use App\Controller\AccountManagementController;
use App\Controller\WorldController;
use App\Doctrine\UuidEncoder;
use App\Entity\Character;
use App\Service\NavbarService;
use App\Util\Session\RhunSession;

/**
 * Description of ListNavbarFactory
 *
 * @author Draeius
 */
class ListNavbarFactory {

    /**
     *
     * @var NavbarService
     */
    private $navbarService;

    function __construct(NavbarService $navbarService) {
        $this->navbarService = $navbarService;
    }

    public function getListNavbar(?Character $character) {
        $session = new RhunSession();
        $tabId = $session->getTabIdentifier();
        if ($tabId && $tabId->hasIdentifier()) {
            $encoder = new UuidEncoder();
            $this->navbarService->addNav('Zurück', WorldController::STANDARD_WORLD_ROUTE_NAME, [
                'uuid' => $tabId->getIdentifier(),
                'locationId' => $encoder->encode($character->getLocation()->getUuid())
            ]);
        } else {
            $this->navbarService->addNav('Zurück', AccountManagementController::ACCOUNT_MANAGEMENT_ROUTE_NAME);
        }
        return $this->navbarService;
    }

}
