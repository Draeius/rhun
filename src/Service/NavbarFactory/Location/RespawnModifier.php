<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service\NavbarFactory\Location;

use App\Entity\Location;
use App\Service\NavbarService;
use App\Util\Session\RhunSession;

/**
 * Description of RespawnModifier
 *
 * @author Draeius
 */
class RespawnModifier extends NavbarModifierBase {

    public function modifyNavbar(NavbarService $service, Location $location) {
        $session = new RhunSession();
        $service
                ->addNavhead('Sonstiges')
                ->addNav('Wiederbeleben', 'revive', ['uuid' => $session->getTabIdentifier()->getIdentifier(), 'fullRevive' => 'true'])
                ->addNav('Edana anbetteln', 'revive', ['uuid' => $session->getTabIdentifier()->getIdentifier(), 'fullRevive' => 'false']);
    }

}
