<?php

namespace App\Controller\Entity;

use App\Controller\BasicController;
use App\Repository\CharacterRepository;
use App\Util\Session\RhunSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @author Draeius
 */
class CharacterController extends BasicController {

    const CHANGE_RP_STATE_ROUTE_NAME = 'change_rp_state';

    /**
     * @Route("/char/change_rp_state/{uuid}", name=CharacterController::CHANGE_RP_STATE_ROUTE_NAME)
     */
    public function changeRpReady($uuid, CharacterRepository $charRepo, EntityManagerInterface $eManager) {
        $session = new RhunSession();
        $character = $charRepo->find($session->getCharacterID());
        $character->setRpState(!$character->getRpState());

        $eManager->persist($character);
        $eManager->flush();

        return $this->redirectToWorld($character);
    }

}
