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
 * Description of LibraryModifier
 *
 * @author Draeius
 */
class LibraryModifier extends NavbarModifierBase {

    public function modifyNavbar(NavbarService $service, Location $location): array {
        $session = new RhunSession();
        $selected = $session->get('bookTheme');
        $session->remove('bookTheme');

        $themes = $this->getManager()->getRepository('App:BookTheme')->findBy([], ['listOrder' => 'ASC']);

        $service->addNavhead('Bibliothek');
        foreach ($themes as $theme) {
            if ($theme->getId() != $selected) {
                $service->addNav($theme->getTheme(), 'change_display_theme',
                        [
                            'uuid' => $session->getTabIdentifier()->getIdentifier(),
                            'theme' => $theme->getId()
                ]);
            }
        }
    }

}
