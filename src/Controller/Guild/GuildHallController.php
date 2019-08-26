<?php

namespace App\Controller\Guild;

use App\Controller\BasicController;
use App\Entity\Character;
use App\Entity\GuildHall;
use App\Entity\Navigation;
use App\Repository\CharacterRepository;
use App\Repository\GuildRepository;
use App\Repository\LocationRepository;
use App\Repository\NavigationRepository;
use App\Util\Session\RhunSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of GuildHallController
 *
 * @author Draeius
 * @Route("/guild/hall")
 * @App\Annotation\Security(needCharacter=true, needAccount=true)
 */
class GuildHallController extends BasicController {

    /**
     *
     * @var CharacterRepository
     */
    private $charRepo;

    /**
     *
     * @var GuildRepository
     */
    private $guildRepo;

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    function __construct(CharacterRepository $charRepo, GuildRepository $guildRepo, EntityManagerInterface $eManager) {
        $this->charRepo = $charRepo;
        $this->guildRepo = $guildRepo;
        $this->eManager = $eManager;
    }

    /**
     * @Route("/edit_room/{uuid}", name="guild_hall_edit_room")
     */
    public function editGuildRoom(Request $request, $uuid, LocationRepository $locRepo, NavigationRepository $navRepo) {
        $session = new RhunSession();
        /* @var $character Character */
        $character = $this->charRepo->find($session->getCharacterID());
        $guild = $character->getGuild();

        if ($guild->getMaster()->getId() != $character->getId()) {
            return $this->redirectToWorld($character);
        }

        $location = $locRepo->find($request->get("location"));


        if (!$guild->getGuildHall()->hasLocation($location)) {
            return $this->redirectToWorld($character);
        }

        $location->setDescriptionSpring($request->get("description_spring"));
        $location->setDescriptionSummer($request->get("description_summer"));
        $location->setDescriptionFall($request->get("description_fall"));
        $location->setDescriptionWinter($request->get("description_winter"));
        $location->setColoredName($request->get("title"));

        $navs = $navRepo->findByTargetLocation($location);
        foreach ($navs as $nav) {
            $nav->setColoredName($location->getTitle());
            $this->eManager->persist($nav);
        }

        $this->eManager->persist($location);
        $this->eManager->flush();

        return $this->redirectToWorld($character);
    }

    private function updateGuildHallLayout(GuildHall $guildHall, $data) {
        foreach ($guildHall->getLocations() as $location) {
            $dataEntry = $this->getLocationFromData($location->getId(), $data);
            if ($dataEntry) {
                $this->checkExistingNavs($location, $dataEntry);
                $this->checkDataNavs($location, $dataEntry);
            }
        }
    }

    private function getLocationFromData(int $id, $data) {
        foreach ($data as $entry) {
            if ($entry['id'] == $id) {
                return $entry;
            }
        }
        return false;
    }

    private function checkExistingNavs(LocationEntity $location, $data) {
        foreach ($location->getNavs() as $nav) {
            $existsInData = false;
            foreach ($data['connections'] as $target) {
                if ($nav->getTargetLocation() && $target == $nav->getTargetLocation()->getId()) {
                    $existsInData = true;
                }
            }
            if (!$existsInData && !$nav->getTargetLocation() instanceof GuildListLocationEntity) {
                $this->getDoctrine()->getManager()->remove($nav);
            }
        }
    }

    private function checkDataNavs(LocationEntity $location, $data) {
        $locRep = $this->getDoctrine()->getRepository('NavigationBundle:LocationEntity');
        foreach ($data['connections'] as $target) {
            $existsInLocation = false;
            foreach ($location->getNavs() as $nav) {
                if ($nav->getTargetLocation() && $target == $nav->getTargetLocation()->getId()) {
                    $existsInLocation = true;
                }
            }
            if (!$existsInLocation) {
                $targetEntity = $locRep->find($target);
                $navigation = new Navigation();
                $navigation->setLabel($targetEntity->getTitle());
                $navigation->setLocation($location);
                $navigation->setTargetLocation($targetEntity);

                $location->addNav($navigation);
                $this->getDoctrine()->getManager()->persist($navigation);
                $this->getDoctrine()->getManager()->persist($location);
            }
        }
    }

}
