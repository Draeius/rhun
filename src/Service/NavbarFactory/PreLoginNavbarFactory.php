<?php

namespace App\Service\NavbarFactory;

use App\Controller\PreLoginController;
use App\Service\NavbarService;

/**
 * Dieser Service baut die Navleisten für den Bereich bevor man sich anmeldet.
 *
 * @author Draeius
 */
class PreLoginNavbarFactory {

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
        $this->navbarService->addNav('Zurück', PreLoginController::FRONT_PAGE_ROUTE_NAME);
        return $this->navbarService;
    }

    /**
     * Generiert die Navbar auf der Startseite
     * 
     * @return NavbarService
     */
    public function getFrontPageNavbar(): NavbarService {
        $this->navbarService->addNav('`8Impressum', PreLoginController::IMPRESSUM_ROUTE_NAME)
                ->addLink('`;Über LoGD', 'about')
//                ->addPopupLink('`*F.A.Q.', 'petition.php?op=faq')
                ->addNav('`?Account erstellen', PreLoginController::CREATE_ACCOUNT_ROUTE_NAME)
                ->addNav('`3Rassenübersicht', PreLoginController::RACE_LIST_ROUTE_NAME)
                ->addNavhead('Die Welt')
                ->addNav('`+Karte', PreLoginController::PRE_LOGIN_MAP_ROUTE_NAME)
                ->addNav('`36ten Sinn nutzen`nKämpferliste', PreLoginController::FIGHTER_LIST_ROUTE_NAME)
                ->addLink('`ISpieleinstellungen', 'settings')
                ->addLink('`mPasswort vergessen?', 'reset_password')
                ->addPopupLink('`b`9Rhûnipedia`b', 'http://www.rhun-logd.de/wiki')
//        ->addNavhead("Die LoGD-Welt")
//        ->addLink("`!LoGD Netz", "logdnet.php?op=list")
                ->addNavhead('Partnerstädte')
                ->addPopupLink('`[H`]a`)u`mp`ot`!st`9a`mdt S`mai`!nt`9-`mO`)m`]a`[r ', 'http://www.saint-omar.de/')
                ->addPopupLink('`LV`Ga`Ql`qe`Un`qd`Qo`Gr`Lia', 'http://www.valendoria.com/index.php')
                ->addPopupLink('`pA`Qk`qi Ka`Qz`pe', 'http://akilogd.de/logd/');
        return $this->navbarService;
    }

}
