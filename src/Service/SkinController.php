<?php

namespace SkinBundle\Controller;

use App\Controller\PreLoginController;
use App\Repository\UserRepository;
use App\Util\Session\RhunSession;
use AppBundle\Controller\BasicController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of SkinController
 *
 * @author Draeius
 */
class SkinController extends BasicController {

    /**
     * @Route("/skin/change")
     */
    public function changeSkin(Request $request, UserRepository $userRepo, EntityManagerInterface $eManager) {
        $session = new RhunSession();
        if ($session->getAccountID()) {
            return $this->redirectToRoute(PreLoginController::FRONT_PAGE_ROUTE_NAME);
        }

        $account = $userRepo->find($session->getAccountID());
        $account->setTemplate($request->get('skin'));
        $eManager->persist($account);
        $eManager->flush();

        return $this->redirectToRoute(PreLoginController::FRONT_PAGE_ROUTE_NAME);
    }

}
