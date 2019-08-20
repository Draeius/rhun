<?php

namespace App\Controller;

use App\Form\CreateAccountFormType;
use App\Form\DTO\CreateUserDTO;
use App\Form\DTO\LoginUserDTO;
use App\Form\DTO\ResetPasswordDTO;
use App\Form\Facade\CreateUserFacade;
use App\Form\ResetPasswordFormType;
use App\Service\ConfigService;
use App\Service\EmailService;
use App\Service\NavbarFactory\PreLoginNavbarFactory;
use App\Service\ParamGenerator\PreLoginParamGenerator;
use App\Service\UserService;
use App\Service\ValidationService;
use App\Util\Session\RhunSession;
use App\Form\LoginFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of PreLoginController
 *
 * @author Draeius
 */
class PreLoginController extends BasicController {

    const FRONT_PAGE_ROUTE_NAME = 'front_page';
    const IMPRESSUM_ROUTE_NAME = 'impressum';
    const RULES_ROUTE_NAME = 'rules';
    const CREATE_ACCOUNT_ROUTE_NAME = 'create_account';
    const RACE_LIST_ROUTE_NAME = 'race_list';
    const PRE_LOGIN_MAP_ROUTE_NAME = 'preLoginMap';
    const RESET_PASSWORD_ROUTE_NAME = 'reset_password';
    const FIGHTER_LIST_ROUTE_NAME = 'public_list';

    /**
     *
     * @var PreLoginNavbarFactory
     */
    private $navbarFactory;

    function __construct(PreLoginNavbarFactory $navbarFactory) {
        $this->navbarFactory = $navbarFactory;
    }

    /**
     * 
     * @Route("/", name=PreLoginController::FRONT_PAGE_ROUTE_NAME)
     */
    public function indexAction(Request $request, PreLoginParamGenerator $paramGenerator) {
        $session = new RhunSession();
        if ($session->getAccountID()) {
            return $this->redirectToRoute(AccountManagementController::ACCOUNT_MANAGEMENT_ROUTE_NAME);
        }
        $userDTO = new LoginUserDTO();
        $form = $this->createForm(LoginFormType::class, $userDTO);
        $form->handleRequest($request);

        return $this->render($this->getSkinFile(), $paramGenerator->getFrontPageParams($form));
    }

    /**
     * 
     * @Route("/rules", name=PreLoginController::RULES_ROUTE_NAME)
     */
    public function showRules(PreLoginParamGenerator $paramGenerator) {
        return $this->render($this->getSkinFile(), $paramGenerator->getImpressumParams());
    }

    /**
     * @Route("/impressum", name=PreLoginController::IMPRESSUM_ROUTE_NAME)
     */
    function showImpressum(PreLoginParamGenerator $paramGenerator) {
        return $this->render($this->getSkinFile(), $paramGenerator->getImpressumParams());
    }

    /**
     * @Route("/settings")
     */
    function showSettings(PreLoginParamGenerator $paramGenerator, ConfigService $configService) {
        return $this->render($this->getSkinFile(), $paramGenerator->getSettingsParams($configService));
    }

    /**
     * @Route("/create", name=PreLoginController::CREATE_ACCOUNT_ROUTE_NAME)
     */
    function showNewAccountForm(Request $request, CreateUserFacade $userFacade, ConfigService $config, EmailService $mailService,
            PreLoginParamGenerator $paramGenerator) {
        $userDTO = new CreateUserDTO();
        $form = $this->createForm(CreateAccountFormType::class, $userDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session = new RhunSession();
            if ($config->getStartSettings()->getNeedEmailValidation()) {
                $user = $userFacade->createUser($userDTO);

                $session->setAccountID($user);

                $mailService->sendMailValidationCode($user);

                return $this->redirectToRoute(AccountManagementController::ACCOUNT_MANAGEMENT_ROUTE_NAME);
            }
        }
        return $this->render($this->getSkinFile(), $paramGenerator->getNewAccountParams($form));
    }

    /**
     * @Route("/public/list", name=PreLoginController::FIGHTER_LIST_ROUTE_NAME)
     */
    function showCharacterList(PreLoginParamGenerator $paramGenerator) {
        return $this->render($this->getSkinFile(), $paramGenerator->getListParams());
    }

    /**
     * 
     * @Route("/about")
     */
    function showAbout(PreLoginParamGenerator $paramGenerator) {
        return $this->render($this->getSkinFile(), $paramGenerator->getAboutParams());
    }

    /**
     * @Route("/races", name=PreLoginController::RACE_LIST_ROUTE_NAME)
     */
    function showRaces(PreLoginParamGenerator $paramGenerator) {
        return $this->render($this->getSkinFile(), $paramGenerator->getRaceListParams());
    }

    /**
     * @Route("/map_image", name=PreLoginController::PRE_LOGIN_MAP_ROUTE_NAME)
     */
    function showMap(PreLoginParamGenerator $paramGenerator) {
        return $this->render($this->getSkinFile(), $paramGenerator->getMapParams());
    }

    /**
     * @todo Send email
     * @Route("/reset_password", name=PreLoginController::RESET_PASSWORD_ROUTE_NAME)
     */
    function showResetPassword(Request $request, ValidationService $service, UserService $uService, EmailService $mailService,
            PreLoginParamGenerator $paramGenerator) {
        $resetDTO = new ResetPasswordDTO();
        $form = $this->createForm(ResetPasswordFormType::class, $resetDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPW = $service->getValidationCode();
            $mailService->sendNewPasswordMail($user, $newPassword);

            $uService->resetUserPassword($newPW);
        }
        return $this->render($this->getSkinFile(), $paramGenerator->getResetPasswordParams());
    }

}
