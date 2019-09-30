<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Character;
use App\Repository\CharacterRepository;
use App\Util\Session\RhunSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of ActivityController
 *
 * @author Draeius
 */
class ActivityController extends BasicController {

    /**
     * @Route("activity/execute/{id}/{uuid}", name="activity_exec")
     */
    public function executeActivity(Request $request, $id, $uuid, EntityManagerInterface $eManager, CharacterRepository $charRepo) {
        $session = new RhunSession();
        /* @var $activity Activity */
        $activity = $eManager->getRepository('App:Activity')->find($id);
        if (!$activity) {
            return $this->redirectToWorld($uuid);
        }

        /* @var $character Character */
        $character = $charRepo->find($session->getCharacterID());
        if ($character->getActionPoints() < 1) {
            $session->error('Du bist zu erschöpft um das zu tun.');
            return $this->redirectToWorld($character);
        }

        $optionPicker = $activity->prepareOptions();
        $option = $optionPicker->chooseOption();

        $character->setActionPoints($character->getActionPoints() - 1);

        if ($option) {
            $option->execute($eManager, $character);
            if ($option->getShortNewsText()) {
                $this->addShortNews($option->getShortNewsText());
            }
            $session->log($option->getText($character));
        }

        $eManager->flush($character);

        return $this->redirectToWorld($character);
    }

}
