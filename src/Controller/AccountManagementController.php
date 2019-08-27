<?php

namespace App\Controller;

use App\Controller\BasicController;
use App\Entity\User;
use App\Form\CreateCharacterType;
use App\Form\DTO\CreateCharacterDTO;
use App\Form\Facade\CreateCharacterFacade;
use App\Repository\BroadcastRepository;
use App\Repository\CharacterRepository;
use App\Repository\UserRepository;
use App\Service\CharacterService;
use App\Service\ConfigService;
use App\Service\DateTimeService;
use App\Service\NavbarFactory\AccountMngmtNavbarFactory;
use App\Service\ParamGenerator\AccountMngmtParamGenerator;
use App\Service\SkinService;
use App\Util\Session\RhunSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Description of AccountManagementController
 *
 * @author Draeius
 * @App\Annotation\Security(needAccount=true)
 */
class AccountManagementController extends BasicController {

    const ACCOUNT_MANAGEMENT_ROUTE_NAME = 'acct_mnmgt';
    const DSGVO_ROUTE_NAME = 'dsgvo';

    /**
     *
     * @var AccountMngmtNavbarFactory 
     */
    private $navFactory;

    /**
     *
     * @var Stopwatch
     */
    private $stopwatch;

    function __construct(SkinService $skinService, AccountMngmtNavbarFactory $navFactory, Stopwatch $stopwatch) {
        parent::__construct($skinService);
        $this->navFactory = $navFactory;
        $this->stopwatch = $stopwatch;
    }

    /**
     * @Route("/account", name=AccountManagementController::ACCOUNT_MANAGEMENT_ROUTE_NAME, defaults={"_fragment"="chars"})
     */
    public function showCharmanagement(Request $request, UserRepository $userRepo, AccountMngmtParamGenerator $paramGenerator, BroadcastRepository $broadcastRepo,
            CreateCharacterFacade $characterFacade, ConfigService $config) {
        $session = new RhunSession();
        /* @var $user User */
        $user = $userRepo->find($session->getAccountID());

        if (!$user->getAcceptTerms()) {
            return $this->redirectToRoute(self::DSGVO_ROUTE_NAME);
        }

        $this->stopwatch->start('form handling');
        $character = new CreateCharacterDTO();
        $form = $this->createForm(CreateCharacterType::class, $character);
        $form->handleRequest($request);
        $this->stopwatch->stop('form handling');

        if ($form->isSubmitted() && $form->isValid()) {
            $characterFacade->createUser($character, $config->getSettings()->getActionPoints());
        }

        return $this->render($this->getSkinFile(), $paramGenerator->getStandardParams($user, $form, $broadcastRepo));
    }

    /**
     * @App\Annotation\Security(needAccount=true)
     * @Route("/dsgvo", name=AccountManagementController::DSGVO_ROUTE_NAME)
     */
    public function showDSGVO() {
        $builder = $this->get('app.navbar');
        $vars = array_merge($this->getBaseVariables($builder, 'Datenschutzerklärung'), [
            'page' => 'default/dsgvo'
        ]);
        return $this->render($this->getSkinFile(), $vars);
    }

    /**
     * @App\Annotation\Security(needAccount=true)
     * @Route("/dsgvo/answer", name="dsgvo_answer")
     */
    public function answerDSGVO(Request $request) {
        $accept = filter_var($request->get('accept', false), FILTER_VALIDATE_BOOLEAN);
        $manager = $this->getDoctrine()->getManager();
        if ($accept) {
            $account = $this->getAccount();
            $account->setAcceptTerms(true);
            $manager->persist($account);
        } else {
            return $this->redirect('/account/delete');
        }
        $manager->flush();
        return $this->redirectToRoute(self::ACCOUNT_MANAGEMENT_ROUTE_NAME);
    }

    /**
     * @App\Annotation\Security(needAccount=true)
     * @Route("/account/set/birthday")
     * @param Request $request
     */
    public function setBirthday(Request $request) {
        if ($request->get('birthday')) {
            $account = $this->getAccount();
            $account->setBirthday(DateTimeService::getDateTime($request->get('birthday')));

            $em = $this->getDoctrine()->getManager();
            $em->persist($account);
            $em->flush();
        }
        return $this->redirectToRoute(self::ACCOUNT_MANAGEMENT_ROUTE_NAME);
    }

    /**
     * @App\Annotation\Security(needAccount=true)
     * @Route("/account/change/password")
     * @param Request $request
     */
    public function changePassword(Request $request) {
        $valid = $this->validateRequest($request, [
            new StringNotNullValidator('pass_old', 3, 200, 'Du musst dein altes Passwort mit angeben.'),
            new StringEqualsValidator('pass1', 'pass2', 3, 200, 'Deine neues Passwort stimmt nicht überein, oder du hast keins angegeben.')
        ]);

        if ($valid) {
            $encoder = $this->container->get('security.password_encoder');
            $user = $this->getAccount();
            if ($encoder->isPasswordValid($user, $request->get('_password'))) {
                $password = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
                $this->getDoctrine()->getManager()->flush($user);
                $session = new RhunSession('');
                $session->log('Dein Passwort wurde erfolgreich geändert.');
            }
        }
        return $this->redirectToRoute(self::ACCOUNT_MANAGEMENT_ROUTE_NAME);
    }

    /**
     * @App\Annotation\Security(needAccount=true)
     * @Route("/account/change/mail")
     */
    public function changeEmail(Request $request) {
        $account = $this->getAccount();
        $account->setEmail($request->get('email'));
        $account->setValidated(FALSE);
        $this->getDoctrine()->getManager()->flush($account);
        EmailUtil::sendValidationEmail($account);

        return $this->redirectToRoute(self::ACCOUNT_MANAGEMENT_ROUTE_NAME);
    }

    /**
     * @App\Annotation\Security(needAccount=true)
     * @Route("/account/verify")
     */
    public function verifyAccount(Request $request) {
        $rep = $this->getDoctrine()->getRepository('App:User');
        $account = $rep->findByName($request->get('name'));

        if ($account && $account->getValidationCode() == $request->get('code')) {
            $account->setValidated(true);
            $this->getDoctrine()->getManager()->flush($account);
        }
        return $this->redirectToRoute(self::ACCOUNT_MANAGEMENT_ROUTE_NAME);
    }

    /**
     * @App\Annotation\Security(needAccount=true)
     * @Route("/account/buy_slot")
     */
    public function buyCharSlot() {
        /* @var $account User */
        $account = $this->getAccount();
        $neededGems = CharacterService::getNeededGems($account);
        $neededPosts = CharacterService::getNeededPosts($account);

        $isAllowed = true;

        $totalPosts = 0;
        if ($account->getGems() < $neededGems) {
            $isAllowed = false;
        } else {
            foreach ($account->getCharacters() as $char) {
                $totalPosts += $char->getPostCounter();
                if ($char->getPostCounter() < 15) {
                    $isAllowed = false;
                }
            }
        }
        if ($totalPosts < $neededPosts) {
            $isAllowed = false;
        }
        if ($isAllowed) {
            $account->setMaxChars($account->getMaxChars() + 1);
            $account->setGems($account->getGems() - $neededGems);
            $this->getDoctrine()->getManager()->flush($account);
        }
        return $this->redirectToRoute(self::ACCOUNT_MANAGEMENT_ROUTE_NAME);
    }

    /**
     * @App\Annotation\Security(needAccount=true)
     * @Route("account/delete")
     */
    public function deleteAccount(UserRepository $userRepo) {
        $session = new RhunSession();
        $account = $userRepo->find($session->getAccountID());
        $session->clearData();
        $manager = $this->getDoctrine()->getManager();
        foreach ($account->getCharacters() as $char) {
            CharacterService::deleteCharacter($manager, $char);
        }
        $manager->remove($account);
        $manager->flush();

        return $this->redirectToRoute(self::ACCOUNT_MANAGEMENT_ROUTE_NAME);
    }

    /**
     * @Route("/char/delete/{char}")
     * @param int $char
     */
    public function deleteChar($char, UserRepository $userRepo, CharacterRepository $charRepo, EntityManagerInterface $eManager) {
        $session = new RhunSession();
        $character = $charRepo->find($char);
        if (!$character) {
            return $this->redirectToRoute(self::ACCOUNT_MANAGEMENT_ROUTE_NAME);
        }
        /* @var $account User */
        $account = $userRepo->find($session->getAccountID());
        if (!$userRepo->ownsCharacter($account, $character)) {
            return $this->redirectToRoute(self::ACCOUNT_MANAGEMENT_ROUTE_NAME);
        }

        CharacterService::deleteCharacter($eManager, $character);

        return $this->redirectToRoute(self::ACCOUNT_MANAGEMENT_ROUTE_NAME);
    }

}
