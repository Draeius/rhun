<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Form\DTO\LoginUserDTO;
use App\Form\LoginFormType;
use App\Repository\UserRepository;
use App\Util\Session\RhunSession;
use App\Controller\AccountManagementController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Description of LoginController
 *
 * @author Matthias
 */
class LoginController extends AbstractController {

    const ACCOUNT_LOGIN_ROUTE_NAME = 'login_account';
    const ACCOUNT_LOGIN_FAIL_FORWARD = PreLoginController::class . '::indexAction';

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

}
