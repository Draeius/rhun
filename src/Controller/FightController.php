<?php

namespace App\Controller;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use App\Repository\MonsterRepository;
use App\Util\Fight\Action\AttackAction;
use App\Util\Fight\Action\FleeAction;
use App\Util\Fight\Fight;
use App\Util\Session\RhunSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of FightController
 *
 * @author Draeius
 * @Route("fight")
 */
class FightController extends BasicController {

    const ATTACK_ROUTE_NAME = 'fight_attack';
    const FLEE_ROUTE_NAME = 'fight_flee';
    const NEW_FIGHT_ROUTE_NAME = 'fight_new';

    /**
     * @Route("/new/{uuid}", name=FightController::NEW_FIGHT_ROUTE_NAME)
     */
    public function newFight($uuid, EntityManagerInterface $eManager, MonsterRepository $monsterRepo) {
        
    }

    /**
     * @Route("/attack/{uuid}/{targetIndex}", name=FightController::ATTACK_ROUTE_NAME)
     */
    public function attack($uuid, $targetIndex, EntityManagerInterface $eManager, CharacterRepository $charRepo, MonsterRepository $monsterRepo) {
        $session = new RhunSession();
        $character = $charRepo->find($session->getCharacterID());

        $fight = $this->getFight($session, $charRepo, $monsterRepo);
        if (!$fight) {
            $this->redirectToWorld($character);
        }
        $fight->doRound(new AttackAction($character, $targetIndex, 0));

        $this->saveFight($fight, $session, $character, $eManager);
        return $this->redirectToWorld($character);
    }

    /**
     * @Route("/flee/{uuid}", name=FightController::FLEE_ROUTE_NAME)
     */
    public function attemptFlee($uuid, EntityManagerInterface $eManager, CharacterRepository $charRepo, MonsterRepository $monsterRepo) {
        $session = new RhunSession();
        $character = $charRepo->find($session->getCharacterID());

        $fight = $this->getFight($session, $charRepo, $monsterRepo);
        $fight->doRound(new FleeAction($character, 0, 0));

        $this->saveFight($fight, $session, $character, $eManager);
        return $this->redirectToWorld($character);
    }

    private function getFight(RhunSession $session, CharacterRepository $charRepo, MonsterRepository $monsterRepo): ?Fight {
        $fightData = $session->getFightData();
        if (!$fightData) {
            return null;
        }
        return Fight::FROM_DATA_STRING($fightData, $charRepo, $monsterRepo);
    }

    private function saveFight(Fight $fight, RhunSession $session, Character &$character, EntityManagerInterface $eManager) {
        foreach ($fight->getCharacterParticipants() as $char) {
            $eManager->persist($char);
        }
        $eManager->flush();
        $eManager->refresh($character);

        $session->setFightData($fight->getDataString());
    }

}
