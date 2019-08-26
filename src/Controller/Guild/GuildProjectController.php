<?php

namespace App\Controller\Guild;

use App\Controller\BasicController;
use App\Entity\Area;
use App\Entity\Attribute;
use App\Entity\Character;
use App\Entity\Guild;
use App\Entity\GuildProject;
use App\Entity\Location;
use App\Repository\AreaRepository;
use App\Repository\CharacterRepository;
use App\Repository\GuildRepository;
use App\Service\GuildProjectService;
use App\Util\Price;
use App\Util\Session\RhunSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of GuildProjectController
 *
 * @author Draeius
 * @Route("/guild/project")
 * @App\Annotation\Security(needCharacter=true, needAccount=true)
 */
class GuildProjectController extends BasicController {

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
     * @Route("/start/{uuid}", name="guild_project_start")
     */
    public function startProject(Request $request, string $uuid, GuildProjectService $projService) {
        $session = new RhunSession();
        /* @var $character Character */
        $character = $this->charRepo->find($session->getCharacterID());
        $guild = $character->getGuild();
        if (!$guild) {
            return $this->redirectToWorld($character);
        }
        if ($guild->getMaster()->getId() != $character->getId()) {
            return $this->redirectToWorld($character);
        }

        $project = $projService->factory($request->get('type'), $guild);
        $manager = $this->getDoctrine()->getManager();

        $manager->persist($project);
        $manager->flush();

        return $this->redirectToWorld($character);
    }

    /**
     * @Route("/contribute/{uuid}", name="guild_project_contribute")
     */
    public function contributeToProject(Request $request, $uuid) {
        $session = new RhunSession();
        /* @var $character Character */
        $character = $this->charRepo->find($session->getCharacterID());
        $guild = $character->getGuild();
        if (!$guild) {
            return $this->redirectToWorld($character);
        }

        $project = $this->eManager->getRepository('App:GuildProject')->find($request->get('project'));

        if (!$project || $project->getGuild()->getId() != $guild->getId()) {
            return $this->redirectToWorld($character);
        }

        $price = new Price($request->get('gold', 0), $request->get('platin', 0), $request->get('gems', 0));

        $this->doContribution($project, $character, $price);

        return $this->redirectToWorld($character);
    }

    /**
     * @Route("/finalize/{uuid}", name="guild_project_finalize")
     */
    public function finalizeProject(Request $request, string $uuid, AreaRepository $areaRepo) {
        $project = $this->getDoctrine()->getRepository('App:GuildProject')->find($request->get('project'));
        $area = $areaRepo->find(17);

        $session = new RhunSession();
        /* @var $character Character */
        $character = $this->charRepo->find($session->getCharacterID());
        if (!$project || !$project->isReady()) {
            return $this->redirectToWorld($character);
        }
        switch ($project->getType()) {
            case GuildProject::BUILD_ROOM_PROJECT:
                $this->finalizeBuildRoomProject($area, $project->getGuild());
                break;
            case GuildProject::BUILD_ROOM_TRAINING:
                $this->finalizeBuildTrainingProject($area, $project->getGuild());
                break;
        }

        $this->eManager->remove($project);
        $this->eManager->flush();
        return $this->redirectToWorld($character);
    }

    private function doContribution(GuildProject $project, Character $character, Price $price) {
        if (!$character->getWallet()->checkPrice($price)) {
            $session = new RhunSession();
            $session->error('Du kannst dir das nicht leisten.');
            return $this->redirectToWorld($character);
        }

        $project->addPrice($price);
        $character->getWallet()->subtractPrice($price);

        $this->eManager->persist($character);
        $this->eManager->persist($project);

        $this->eManager->flush();
    }

    private function finalizeBuildRoomProject(Area $area, Guild $guild) {
        $location = new Location();
        $location->setAdult(FALSE);
        $location->setArea($area);
        $location->setDescriptionSpring('Ein leerer Raum.');
        $location->setColoredName('Leerer Raum');

        $guild->getGuildHall()->addLocation($location);

        $this->eManager->persist($location);
        $this->eManager->persist($guild->getGuildHall());
    }

    private function finalizeBuildTrainingProject(Area $area, Guild $guild) {
        $location = new Location();
        $location->setAdult(FALSE);
        $location->setArea($area);
        $location->setDescriptionSpring('Ein leerer Trainingsraum.');
        $location->setColoredName('Trainingsraum');
        $location->setGuildTraining(true);

        $guild->setBuffedAttribute(Attribute::STRENGTH);
        $guild->getGuildHall()->addLocation($location);

        $this->eManager->persist($location);
        $this->eManager->persist($guild);
        $this->eManager->persist($guild->getGuildHall());
    }

}
