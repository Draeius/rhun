<?php

namespace App\Controller;

use App\Repository\CharacterRepository;
use App\Service\ParamGenerator\ListParamGenerator;
use App\Util\Session\RhunSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of ListController
 *
 * @author Draeius
 * @Route("/list")
 */
class ListController extends BasicController{

    const CHAR_LIST_ROUTE_NAME = 'char_list_all';

    /**
     * @Route("/chars", name=ListController::CHAR_LIST_ROUTE_NAME)
     */
    public function showCharList(EntityManagerInterface $eManager, ListParamGenerator $paramGenerator) {
        return $this->render($this->getSkinFile(), $paramGenerator->getCharListParams(null, $eManager));
    }

    /**
     * @Route("/chars/{uuid}", name=ListController::CHAR_LIST_ROUTE_NAME)
     */
    public function showCharListWithUuid($uuid, EntityManagerInterface $eManager, CharacterRepository $charRepo, ListParamGenerator $paramGenerator) {
        $session = new RhunSession();
        $character = $charRepo->find($session->getCharacterID());

        return $this->render($this->getSkinFile(), $paramGenerator->getCharListParams($character, $eManager));
    }

}
