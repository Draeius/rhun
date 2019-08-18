<?php

namespace App\Controller;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use App\Util\Session\RhunSession;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of LogoutController
 *
 * @author Draeius
 * @Route("/logout")
 */
class LogoutController extends AbstractController {

    const LOGOUT_ACCOUNT_ROUTE_NAME = 'logout_account';
    const LOGOUT_CHARACTER_ROUTE_NAME = 'logout_char';

    /**
     * @App\Annotation\Security(needAccount=true)
     * @Route("/account", name=LogoutController::LOGOUT_ACCOUNT_ROUTE_NAME)
     */
    public function logoutAccount() {
//        $account = $this->getAccount();
//        $characters = $account->getCharacters();
//        $manager = $this->getDoctrine()->getManager();
//        foreach ($characters as $char) {
//            if ($char->getOnline()) {
//                $char->setOnline(false);
//                $char->setSafe(false);
//                $manager->persist($char);
//            }
//        }
//        $manager->flush();

        $session = new RhunSession();
        $session->clear();

        return $this->redirectToRoute(PreLoginController::FRONT_PAGE_ROUTE_NAME);
    }

    /**
     * App\Annotation\Security(needAccount=true, needCharacter=true)
     * @Route("/char/{charUuid}/{safe}", name=LogoutController::LOGOUT_CHARACTER_ROUTE_NAME)
     */
    public function logoutChar(CharacterRepository $charRepo, string $charUuid, bool $safe) {
        $em = $this->getDoctrine()->getManager();
        /* @var $character Character */
        $character = $charRepo->findOneByEncodedUuid($charUuid);

        $session = new RhunSession();
        $session->clearDataForChar($character);

        $character->setOnline(false);
        $character->setSafe($safe == true);
        $em->persist($character);
        $em->flush();

        return $this->redirectToRoute(AccountManagementController::ACCOUNT_MANAGEMENT_ROUTE_NAME);
    }

}
