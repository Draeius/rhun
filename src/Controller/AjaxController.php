<?php

namespace App\Controller;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use App\Repository\GuildRepository;
use App\Repository\HouseRepository;
use App\Repository\LocationRepository;
use App\Service\ConfigService;
use App\Service\NavigationUpdater;
use App\Util\Session\RhunSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AjaxController
 *
 * @author Draeius
 */
class AjaxController extends BasicController {

    /**
     * @Route("/location/get")
     */
    public function getLocationById(Request $request, LocationRepository $locRepo) {
        $location = $locRepo->find($request->get("location"));
        if (!isset($location) || !$location) {
            return new JsonResponse([]);
        }
        $array = [
            "title" => $location->getColoredName(),
            "description_spring" => $location->getDescriptionSpring(),
            "description_summer" => $location->getDescriptionSummer(),
            "description_fall" => $location->getDescriptionFall(),
            "description_winter" => $location->getDescriptionWinter(),
            "area" => $location->getArea()->getId(),
            'cs' => $location->getAdult()
        ];

        return new JsonResponse($array);
    }

    /**
     * @Route("/house/workroom/update/{uuid}")
     */
    public function saveHouseLayout(Request $request, string $uuid, NavigationUpdater $navUpdater, CharacterRepository $charRepo,
            HouseRepository $houseRepo, EntityManagerInterface $eManager, ConfigService $config) {
        $data = json_decode($request->get('data', '[]'), true);
        $session = new RhunSession();

        if (!$data) {
            return new JsonResponse(json_encode(['ERR' => 'Ein Problem bei der Datenübermittlung']));
        }
        /* @var $character Character */
        $character = $charRepo->find($session->getCharacterId());

        $price = $config->getHouseConfig()->getNavUpdatePrice();
        if (!$character->getWallet()->checkPrice($price)) {
            return new JsonResponse(json_encode(['ERR' => 'Das kannst du dir nicht leisten.']));
        }
        $character->getWallet()->addPrice($price->multiply(-1));

        $house = $houseRepo->findByLocation($character->getLocation());
        if (!$house || $house->getOwner()->getId() != $character->getId()) {
            return new JsonResponse(json_encode(['ERR' => 'Das Haus gehört dir nicht.']));
        }

        $navUpdater->update($house->getRooms(), $data);
        $eManager->persist($character);
        $eManager->flush();
        return new JsonResponse(json_encode(['MSG' => 'Erfolgreich gespeichert.']));
    }

    /**
     * @Route("/guild/hall/save/{uuid}", name="guild_hall_save")
     */
    public function saveGuildHallLayout(Request $request, string $uuid, NavigationUpdater $navUpdater, CharacterRepository $charRepo,
            EntityManagerInterface $eManager, ConfigService $config) {
        $data = json_decode($request->get('data', '[]'), true);
        $session = new RhunSession();

        if (!$data) {
            return new JsonResponse(json_encode(['ERR' => 'Ein Problem bei der Datenübermittlung']));
        }

        /* @var $character Character */
        $character = $charRepo->find($session->getCharacterId());

        $price = $config->getGuildConfig()->getNavUpdatePrice();
        if (!$character->getWallet()->checkPrice($price)) {
            return new JsonResponse(json_encode(['ERR' => 'Das kannst du dir nicht leisten.']));
        }
        $character->getWallet()->addPrice($price->multiply(-1));

        $guild = $character->getGuild();
        if (!$guild || $guild->getMaster()->getId() != $character->getId()) {
            return new JsonResponse(json_encode(['ERR' => 'Die Gilde gehört dir nicht.']));
        }

        $navUpdater->update($guild->getGuildHall()->getLocations(), $data);
        $eManager->persist($character);
        $eManager->flush();

        return new JsonResponse(json_encode(['MSG' => 'Erfolgreich gespeichert.']));
    }

}
