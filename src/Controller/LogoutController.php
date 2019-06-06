<?php

namespace App\Controller;

use App\Util\Session\RhunSession;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of LogoutController
 *
 * @author Matthias
 */
class LogoutController extends AbstractController {

    const LOGOUT_ACCOUNT_ROUTE_NAME = 'logout_account';
    
    /**
     * @App\Annotation\Security(needAccount=true)
     * @Route("/account/logout", name=LogoutController::LOGOUT_ACCOUNT_ROUTE_NAME)
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

}
