<?php

namespace App\Service\NavbarFactory;

use App\Controller\AccountManagementController;
use App\Service\NavbarService;

/**
 * Description of EditorNavbarFactory
 *
 * @author Draeius
 */
class EditorNavbarFactory {

    /**
     *
     * @var NavbarService
     */
    private $navbarService;

    function __construct(NavbarService $navbarService) {
        $this->navbarService = $navbarService;
    }

    /**
     * Generiert die Standardnavbar, die einen wieder zurück zur Frontpage bringt.
     * @return NavbarService
     */
    public function getDefaultNavbar(): NavbarService {
        $this->navbarService->addNav('Zurück', AccountManagementController::ACCOUNT_MANAGEMENT_ROUTE_NAME);
        return $this->navbarService;
    }

}
