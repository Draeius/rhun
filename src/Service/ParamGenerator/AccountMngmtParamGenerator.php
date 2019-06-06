<?php

namespace App\Service\ParamGenerator;

use App\Entity\User;
use App\Query\GetIsPostAnsweredQuery;
use App\Query\GetOnlineCharactersQuery;
use App\Query\GetRaceListQuery;
use App\Query\GetTotalNumberOfPostsQuery;
use App\Repository\AreaRepository;
use App\Service\CharacterService;
use App\Service\DateTimeService;
use App\Service\NavbarFactory\AccountMngmtNavbarFactory;
use App\Service\SkinService;
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
     * @var EntityManagerInterface
     */
    private $eManager;

    function __construct(DateTimeService $dtService, AreaRepository $areaRepo, SkinService $skinService, AccountMngmtNavbarFactory $navFactory,
            EntityManagerInterface $eManager) {
        parent::__construct($dtService);
        $this->areaRepo = $areaRepo;
        $this->skinService = $skinService;
        $this->navFactory = $navFactory;
        $this->eManager = $eManager;
    }

    public function getStandardParams(User $user, FormInterface $form) {

//        $codingBroadcast = $this->getDoctrine()->getRepository('App:Broadcast')->findBy(array('codingBroadcast' => true), array('id' => 'DESC'), 1);
//        if ($codingBroadcast) {
//            $codingBroadcast = $codingBroadcast[0];
//        }
//        $broadcast = $this->getDoctrine()->getRepository('App:Broadcast')->findBy(array('codingBroadcast' => false), array('id' => 'DESC'), 1);
//        if ($broadcast) {
//            $broadcast = $broadcast[0];
//        }
        
        $userOnlineQuery = new GetOnlineCharactersQuery($this->eManager);
        $totalPostsQuery = new GetTotalNumberOfPostsQuery($this->eManager);
        $answeredPostsQuery = new GetIsPostAnsweredQuery($this->eManager);
        $getRaceListQuery = new GetRaceListQuery($this->eManager);
        $vars = array_merge($this->getBaseParams('Accountverwaltung', $this->navFactory->buildNavbar($user)), $this->areaRepo->getDescriptionOfMajorAreas(), [
            'page' => 'accountManager/accountManager',
            'email' => $user->getEmail(),
            'news' => '', //$broadcast,
            'codingNews' => '', // $codingBroadcast,
            'cities' => AreaRepository::findColoredCityNames(),
            'races' => $getRaceListQuery(),
            'account' => $user,
            'form' => $form->createView(),
            'chars' => [], //$this->getDoctrine()->getRepository('App:Character')->findByAccount($user),
            'neededGems' => CharacterService::getNeededGems($user),
            'neededPosts' => CharacterService::getNeededPosts($user),
            'totalPosts' => $totalPostsQuery($user->getId()),
            'newposts' => $answeredPostsQuery($user->getId()),
            'userOnline' => $userOnlineQuery(),
            'skinlist' => $this->skinService->getSkinList()
        ]);
        return $vars;
    }

}
