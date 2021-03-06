<?php

namespace App\Controller;

use App\Controller\BasicController;
use App\Entity\User;
use App\Form\CreateCharacterFormType;
use App\Form\DTO\CreateCharacterDTO;
use App\Repository\UserRepository;
use App\Service\CharacterService;
use App\Service\DateTimeService;
use App\Service\NavbarFactory\AccountMngmtNavbarFactory;
use App\Service\ParamGenerator\AccountMngmtParamGenerator;
use App\Util\Session\RhunSession;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AccountManagementController
 *
 * @author Draeius
 */
class AccountManagementController extends BasicController {

    const ACCOUNT_MANAGEMENT_ROUTE_NAME = 'acct_mnmgt';
    const DSGVO_ROUTE_NAME = 'dsgvo';

    /**
     *
     * @var AccountMngmtNavbarFactory 
     */
    private $navFactory;

    function __construct(AccountMngmtNavbarFactory $navFactory) {
        $this->navFactory = $navFactory;
    }

    /**
     * @App\Annotation\Security(needAccount=true)
     * @Route("/account", name=AccountManagementController::ACCOUNT_MANAGEMENT_ROUTE_NAME, defaults={"_fragment"="chars"})
     */
    public function showCharmanagement(Request $request, UserRepository $userRepo, AccountMngmtParamGenerator $paramGenerator) {
        $session = new RhunSession();
        /* @var $user User */
        $user = $userRepo->find($session->getAccountID());

        if (!$user->getAcceptTerms()) {
            return $this->redirectToRoute(self::DSGVO_ROUTE_NAME);
        }

        $characterDTO = new CreateCharacterDTO();
        $form = $this->createForm(CreateCharacterFormType::class, $characterDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
        }

        return $this->render($this->getSkinFile(), $paramGenerator->getStandardParams($user, $form));
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
     * @Route("/createchar", name="create_char")
     */
    public function createCharacter(Request $request) {
        $this->cleanSession();

        $this->validateRequest($request, array(
            new StringNotNullValidator('name', 3, 32, 'Dein Name muss mindestens 3 und maximal 32 Zeichen lang sein.'),
            new NumberNotNullValidator('gender', 'Du musst ein Geschlecht auswählen.'),
            new NumberNotNullValidator('race', 'Du musst eine Rasse auswählen.')
        ));

        $success = $this->checkCharSlots();
        $success = $success && $this->checkCharName($request->get('name'));

        $em = $this->getDoctrine()->getManager();

        $race = $em->getRepository('App:Race')->find($request->get('race'));
        $success = $success && $this->checkCharRace($race);
        echo('test ' . $success);

        /* @var $charService CharacterService */
        $charService = $this->get('app.character');
        $character = $charService->createChar($request->get('name'), $request->get('gender'), $this->getAccount(), $race
                , WeaponService::getStartingWeapon($em), ArmorService::getStartingArmor($em));

        if (!$success) {
            return $this->redirectToRoute(self::ACCOUNT_MANAGEMENT_ROUTE_NAME, [
                        'request' => $request,
                        '_fragment' => 'create'
                            ], 307);
        }

        $em->persist($character);

        $this->addShortNews('`jwurde soeben zum ersten Mal in Rhûn gesichtet.', $character);

        $em->flush();
        return $this->redirectToRoute(self::ACCOUNT_MANAGEMENT_ROUTE_NAME, ['_fragment' => 'chars']);
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
    public function deleteAccount() {
        $account = $this->getAccount();
        $session = new RhunSession();
        $session->clearData();
        $manager = $this->getDoctrine()->getManager();
        foreach ($account->getCharacters() as $char) {
            CharacterService::deleteCharacter($manager, $char);
        }
        $manager->remove($account);
        $manager->flush();

        return $this->redirectToRoute(self::ACCOUNT_MANAGEMENT_ROUTE_NAME);
    }

    private function buildNavbar(User $account) {
        $count = 0;

        $rep = $this->getDoctrine()->getRepository('App:Message');
        foreach ($account->getCharacters() as $char) {
            $count += count($rep->findNewMessages($char));
        }

        $builder = $this->get('app.navbar');
        if ($account->isBanned() || !$account->getValidated()) {
            $builder->addNav('Bio Editor (gesperrt)', self::ACCOUNT_MANAGEMENT_ROUTE_NAME)
                    ->addNav('Tagebuch schreiben (gesperrt)', self::ACCOUNT_MANAGEMENT_ROUTE_NAME)
                    ->addNav('Taubenschlag (gesperrt)', self::ACCOUNT_MANAGEMENT_ROUTE_NAME);
        } else {
            $builder->addNav('Bio Editor', 'bioEditor')
                    ->addNav('Tagebuch schreiben', 'diary_editor')
                    ->addNav('Taubenschlag' . ($count > 0 ? ' (Neu: ' . $count . ')' : ''), 'mail_show');
            if ($account->getUserRole()->getWriteMotd()) {
                $builder->addNavhead('Motd')
                        ->addNav('Motd-Creator', 'motd_editor', []);
            }
            if ($account->getUserRole()->getEditWorld()) {
                $builder->addNav('Einstellungen', 'settingsEditor');
            }
            if ($account->getUserRole()->getEditMonster()) {
                $builder->addNavhead('Editoren')
                        ->addNav('Monster', 'admin_monster', [])
                        ->addNav('Aktivitäten', 'admin_activity', []);
            }
            if ($account->getUserRole()->getReviewPosts()) {
                $builder->addNavhead('Mod')
                        ->addNav('Postübersicht', 'post-list');
            }
        }
        $builder->addNavhead('Sonstiges')
                ->addNav('Karte', 'map')
                ->addNav('Regeln', 'rules')
                ->addNav('Kämpferliste', 'list')
                ->addPopupLink('Discord', 'https://discord.gg/Yu8tYxF')
                ->addPopupLink('Wiki', 'http://www.rhun-logd.de/wiki')
                ->addNavhead('Abmelden')
                ->addNav('Abmelden', 'account_logout', []);

        return $builder;
    }

    private function getDescriptions($em) {
        $repo = $em->getRepository('NavigationBundle:Area');

        $seiya = $repo->findOneBy(['city' => 'seiya']);
        $lerentia = $repo->findOneBy(['city' => 'lerentia']);
        $manosse = $repo->findOneBy(['city' => 'manosse']);
        $pyra = $repo->findOneBy(['city' => 'pyra']);
        $nelaris = $repo->findOneBy(['city' => 'nelaris']);
        $underworld = $repo->findOneBy(['city' => 'underworld']);

        return [
            'seiyaDescr' => $seiya->getDescription(),
            'lerentiaDescr' => $lerentia->getDescription(),
            'pyraDescr' => $pyra->getDescription(),
            'manosseDescr' => $manosse->getDescription(),
            'nelarisDescr' => $nelaris->getDescription(),
            'underworldDescr' => $underworld->getDescription()
        ];
    }

    private function checkCharSlots() {
        $acct = $this->getAccount();
        if (count($acct->getCharacters()) >= $acct->getMaxChars()) {
            $session = new RhunSession('');
            $session->getFlashBag()->add('error', 'Du hast bereits die maximale Anzahl an Charakteren. Bitte kaufe erst neue Plätze.`n');
            return false;
        }
        return true;
    }

    private function checkCharName($name) {
        $char = $this->getDoctrine()->getManager()->getRepository('App:Character')->findOneBy(array('name' => $name));
        if (!$char) {
            return true;
        }
        $session = new RhunSession('');
        $session->getFlashBag()->add('error', 'Dieser Name wird bereits benutzt.');
        return false;
    }

    private function checkCharRace(Race $race) {
        if (!$race->getAllowed()) {
            $session = new RhunSession('');
            $session->getFlashBag()->add('error', 'Diese Rasse ist nicht erlaubt.');
            return false;
        }
        return true;
    }

    private function hasNewPost(User $account) {
        $chars = $account->getCharacters();
        $result = [];
        $repo = $this->getDoctrine()->getManager()->getRepository('App:Post');
        foreach ($chars as $character) {
            $result[$character->getId()] = $repo->hasBeenAnswered($character) ? '`c`dJa`c' : '`c`$Nein`c';
        }
        return $result;
    }

}
