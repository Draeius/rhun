<?php

namespace App\Service\ParamGenerator;

use App\Entity\Character;
use App\Query\GetCharacterTotalListQuery;
use App\Service\DateTimeService;
use App\Service\NavbarFactory\ListNavbarFactory;
use App\Util\Session\RhunSession;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of ListParamGenerator
 *
 * @author Draeius
 */
class ListParamGenerator extends ParamGenerator {

    /**
     *
     * @var ListNavbarFactory
     */
    private $navbarFactory;

    function __construct(DateTimeService $dtService, ListNavbarFactory $navbarFactory) {
        parent::__construct($dtService);
        $this->navbarFactory = $navbarFactory;
    }

    public function getCharListParams(?Character $character, EntityManagerInterface $eManager) {
        $session = new RhunSession();
        $query = new GetCharacterTotalListQuery($eManager);
        return array_merge($this->getBaseParams('Kämpferliste', $this->navbarFactory->getListNavbar($character)), [
            'page' => 'list/charList',
            'account' => $eManager->getRepository('App:User')->find($session->getAccountID()),
            'character' => $character,
            'characters' => $query(),
            'dateNow' => DateTimeService::getDateTime('NOW')
        ]);
    }

}
