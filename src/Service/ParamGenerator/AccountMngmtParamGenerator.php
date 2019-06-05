<?php

namespace App\Service\ParamGenerator;

use App\Repository\AreaRepository;
use App\Service\DateTimeService;
use App\Service\SkinService;

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

    function __construct(DateTimeService $dtService, AreaRepository $areaRepo, SkinService $skinService) {
        parent::__construct($dtService);
        $this->areaRepo = $areaRepo;
        $this->skinService = $skinService;
    }

    public function getStandardParams() {
        $skinService = new SkinService($this->get('kernel')->getRootDir());
        $vars = array_merge($this->getBaseParams('Accountverwaltung', $this->navFactory->buildNavbar($user)), $this->areaRepo->getDescriptionOfMajorAreas(), [
            'page' => 'default/charmanagement',
            'email' => $user->getEmail(),
            'news' => '', //$broadcast,
            'codingNews' => '', // $codingBroadcast,
            'cities' => AreaService::getColoredCityNames(),
            'account' => $user,
            'chars' => $this->getDoctrine()->getRepository('App:Character')->findByAccount($user),
            'neededGems' => CharacterService::getNeededGems($user),
            'neededPosts' => CharacterService::getNeededPosts($user),
            'totalPosts' => CharacterService::getTotalPosts($user),
            'newposts' => $this->hasNewPost($user),
            'userOnline' => '', //$this->getDoctrine()->getManager()->getRepository('App:Character')->findByOnline(true),
            'skinlist' => $skinService->getSkinList()
        ]);
        return $vars
    }

}
