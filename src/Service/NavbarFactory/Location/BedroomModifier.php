<?php

namespace App\Service\NavbarFactory\Location;

use App\Controller\LogoutController;
use App\Doctrine\UuidEncoder;
use App\Entity\Location;
use App\Service\NavbarService;

/**
 * Description of BedroomModifier
 *
 * @author Draeius
 */
class BedroomModifier extends NavbarModifierBase {

    public function modifyNavbar(NavbarService $service, Location $location) {
        $encoder = new UuidEncoder();
        $service->addNavhead('Sonstiges')
                ->addNav('Schlafen gehen', LogoutController::LOGOUT_CHARACTER_ROUTE_NAME, [
                    'charUuid' => $encoder->encode($this->getCharacter()->getUuid()),
                    'safe' => 'true'
        ]);
    }

}
