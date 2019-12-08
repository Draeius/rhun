<?php

namespace App\Service\ParamGenerator;

use App\Controller\LoginController;
use App\Controller\LogoutController;
use App\Entity\User;
use App\Query\GetIsPostAnsweredQuery;
use App\Query\GetOnlineCharactersQuery;
use App\Query\GetRaceListQuery;
use App\Query\GetTotalNumberOfPostsQuery;
use App\Repository\AreaRepository;
use App\Repository\BroadcastRepository;
use App\Repository\CharacterRepository;
use App\Service\CharacterService;
use App\Service\DateTimeService;
use App\Service\NavbarFactory\AccountMngmtNavbarFactory;
use App\Service\SkinService;
use App\Util\Session\RhunSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Description of AccountMngmtParamGenerator
 *
 * @author Draeius
 */
class AccountMngmtParamGenerator extends ParamGenerator {

    /**
     *
     * @var AreaRepository
     */
    private $areaRepo;

    /**
     *
     * @var SkinService
     */
    private $skinService;

    /**
     *
     * @var CharacterRepository
     */
    private $charRepo;

    /**
     *
     * @var AccountMngmtNavbarFactory
     */
    private $navFactory;

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    function __construct(DateTimeService $dtService, AreaRepository $areaRepo, SkinService $skinService, CharacterRepository $charRepo,
            AccountMngmtNavbarFactory $navFactory, EntityManagerInterface $eManager) {
        parent::__construct($dtService);
        $this->areaRepo = $areaRepo;
        $this->skinService = $skinService;
        $this->charRepo = $charRepo;
        $this->navFactory = $navFactory;
        $this->eManager = $eManager;
    }

    public function getDSGVOParams(){
        return array_merge($this->getBaseParams('DSGVO', $this->navFactory->buildEmptyNavbar()), [
            'page' => 'accountManager/dsgvo'
        ]);
    }
    
    
    public function getStandardParams(User $user, FormInterface $form, BroadcastRepository $repo) {
        $codingBroadcast = $repo->findNewestBroadcast(true);
        $broadcast = $repo->findNewestBroadcast(false);

        $userOnlineQuery = new GetOnlineCharactersQuery($this->eManager);
        $totalPostsQuery = new GetTotalNumberOfPostsQuery($this->eManager);
        $answeredPostsQuery = new GetIsPostAnsweredQuery($this->eManager);
        $getRaceListQuery = new GetRaceListQuery($this->eManager);
        $vars = array_merge($this->getBaseParams('Accountverwaltung', $this->navFactory->buildNavbar($user)), $this->areaRepo->getDescriptionOfMajorAreas(), [
            'page' => 'accountManager/accountManager',
            'email' => $user->getEmail(),
            'news' => $broadcast,
            'codingNews' => $codingBroadcast,
            'cities' => AreaRepository::findColoredCityNames(),
            'races' => $getRaceListQuery(),
            'account' => $user,
            'form' => $form->createView(),
            'chars' => $this->charRepo->findByAccount($user),
            'neededGems' => CharacterService::getNeededGems($user),
            'neededPosts' => CharacterService::getNeededPosts($user),
            'totalPosts' => $totalPostsQuery($user->getId()),
            'newposts' => $answeredPostsQuery($user->getId()),
            'userOnline' => $userOnlineQuery(),
            'loginRouteName' => LoginController::CHARACTER_LOGIN_ROUTE_NAME,
            'charLogoutRouteName' => LogoutController::LOGOUT_CHARACTER_ROUTE_NAME,
            'skinlist' => $this->skinService->getSkinList()
        ]);
        return $vars;
    }

    public function getMailParams($charId, $charName) {
        $session = new RhunSession();
        /* @var $account User */
        $account = $this->eManager->getRepository('App:User')->find($session->getAccountID());

        if ($session->getTabIdentifier()->hasIdentifier()) {
            $vars = $this->getBaseParams('Taubenschlag', $this->navFactory->buildMailNavbar($this->charRepo->find($session->getCharacterID())));
        } else {
            $vars = $this->getBaseParams('Taubenschlag', $this->navFactory->buildMailNavbar(null));
        }

        $vars['page'] = 'accountManager/mail';
        $vars['chars'] = $this->charRepo->findByAccount($account);
        $vars['messageRep'] = $this->eManager->getRepository('App:Message');
        $vars['selectedChar'] = $charId;
        $vars['targetName'] = $charName;
        return $vars;
    }

}
