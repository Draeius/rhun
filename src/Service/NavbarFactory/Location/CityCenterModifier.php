<?php

namespace App\Service\NavbarFactory\Location;

use App\Controller\ListController;
use App\Controller\LogoutController;
use App\Doctrine\UuidEncoder;
use App\Entity\Location;
use App\Service\NavbarService;
use App\Util\Session\RhunSession;

/**
 * Description of CityCenterModifier
 *
 * @author Draeius
 */
class CityCenterModifier extends NavbarModifierBase {

    public function modifyNavbar(NavbarService $service, Location $location) {
        $encoder = new UuidEncoder();
        $session = new RhunSession();
        $service->addNavhead('Sonstiges')
                ->addNav('Schlafen gehen', LogoutController::LOGOUT_CHARACTER_ROUTE_NAME, [
                    'charUuid' => $encoder->encode($this->getCharacter()->getUuid()),
                    'safe' => 'false'
                ])
                ->addNav('Kämpferliste', ListController::CHAR_LIST_ROUTE_NAME, ['uuid' => $session->getTabIdentifier()->getIdentifier()]);
    }

}
