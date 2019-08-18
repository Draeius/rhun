<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Controller\AccountManagementController;
use App\Entity\Character;
use App\Form\DTO\LoginUserDTO;
use App\Form\LoginFormType;
use App\Repository\CharacterRepository;
use App\Repository\UserRepository;
use App\Service\TabIdentifierService;
use App\Util\Session\RhunSession;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Description of LoginController
 *
 * @author Matthias
 */
class LoginController extends BasicController {

    const ACCOUNT_LOGIN_ROUTE_NAME = 'login_account';
    const ACCOUNT_LOGIN_FAIL_FORWARD = PreLoginController::class . '::indexAction';
    const CHARACTER_LOGIN_ROUTE_NAME = 'login_char';

    /**
     * @Route("/login", name=LoginController::ACCOUNT_LOGIN_ROUTE_NAME)
     */
    public function loginAction(Request $request, UserRepository $userRepo, UserPasswordEncoderInterface $pwEncoder) {
        $userDTO = new LoginUserDTO();
        $form = $this->createForm(LoginFormType::class, $userDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session = new RhunSession();
            if (!empty($result = $userRepo->findByUsername($userDTO->username))) {
                $user = $result[0];
            }
            if ($user && $pwEncoder->isPasswordValid($user, $userDTO->password)) {
                $session = new RhunSession();
                $session->setAccountID($user->getId());
                return $this->redirectToRoute(AccountManagementController::ACCOUNT_MANAGEMENT_ROUTE_NAME, ['_fragment' => 'news']);
            }
        }
        return $this->forward(self::ACCOUNT_LOGIN_FAIL_FORWARD);
    }

    /**
     * @Route("/login/{charUuid}", name=LoginController::CHARACTER_LOGIN_ROUTE_NAME)
     * @App\Annotation\Security(needAccount=true)
     */
    public function loginCharacterAction(CharacterRepository $charRepo, UserRepository $userRepo, string $charUuid) {
        $session = new RhunSession();
        /* @var $character Character */
        $character = $charRepo->findOneByEncodedUuid($charUuid);

        if (!$character) {
            $session->getFlashBag()->add('error', 'Dieser Charakter existiert nicht.');
            return $this->redirectToRoute(AccountManagementController::ACCOUNT_MANAGEMENT_ROUTE_NAME);
        }
        if ($character->getOnline()) {
            $session->getFlashBag()->add('error', 'Dieser Charakter ist bereits angemeldet.');
            return $this->redirectToRoute(AccountManagementController::ACCOUNT_MANAGEMENT_ROUTE_NAME);
        }
        $account = $userRepo->find($session->getAccountID());
        if (!$account->equals($character->getAccount())) {
            $session->getFlashBag()->add('error', 'Dieser Charakter gehört dir nicht.');
            return $this->redirectToRoute(AccountManagementController::ACCOUNT_MANAGEMENT_ROUTE_NAME);
        }

        $tabService = new TabIdentifierService();
        $session->clearDataForChar($character);
        $session->SET_TAB_IDENTIFIER($tabService->getUnusedTabIdentifier());
        $this->setTabIdentifier($session->getTabIdentifier());
        $session->setCharacterID($character->getId());

        $character->setOnline(true);
        $this->getDoctrine()->getManager()->flush($character);

        return $this->redirectToWorld($character);
    }

}
