<?php

namespace App\Controller;

use App\Repository\CharacterRepository;
use App\Util\Session\RhunSession;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of LibraryController
 *
 * @author Draeius
 */
class LibraryController extends BasicController {

    const CHANGE_DISPLAY_THEME_ROUTE_NAME = 'change_display_theme';

    /**
     * @Route("/book/change/display_theme/{uuid}/{theme}", name=LibraryController::CHANGE_DISPLAY_THEME_ROUTE_NAME)
     */
    public function changeDisplayedBookTheme($uuid, $theme, CharacterRepository $charRepo) {
        $session = new RhunSession();
        $session->setBookTheme($theme);

        return $this->redirectToRoute(WorldController::STANDARD_WORLD_ROUTE_NAME,
                        [
                            'uuid' => $this->getTabIdentifier()->getIdentifier(),
                            'locationId' => $charRepo->find($session->getCharacterID())->getLocation()->getId()
        ]);
    }

}
