<?php

namespace App\Controller;

use App\Entity\BurglarAlarm;
use App\Entity\Character;
use App\Entity\House;
use App\Entity\Location;
use App\Repository\CharacterRepository;
use App\Repository\HouseRepository;
use App\Repository\LocationRepository;
use App\Service\CharacterService;
use App\Service\ConfigService;
use App\Service\FormatService;
use App\Service\HouseService;
use App\Service\ParamGenerator\BiographyParamGenerator;
use App\Util\Config\HouseConfig;
use App\Util\House\Intrusion;
use App\Util\Price;
use App\Util\Session\RhunSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Description of HouseController
 *
 * @author Draeius
 * @Route("/house")
 * @App\Annotation\Security(needCharacter=true, needAccount=true)
 */
class HouseController extends BasicController {

    const BUILD_HOUSE_ROUTE_NAME = 'house_build';
    const UPDATE_NAVS_ROUTE_NAME = 'workroom_update';
    const ENTER_HOUSE_ROUTE_NAME = 'house_enter';
    const BURGLAR_HOUSE_ROUTE_NAME = 'burglar';
    const SHOW_HOUSE_ROUTE_NAME = 'house_show';
    const EDIT_ROOM_ROUTE_NAME = 'room_edit';
    const EDIT_HOUSE_ROUTE_NAME = 'house_edit';
    const ADD_INHABITANT_ROUTE_NAME = 'add_inhabitant';
    const REMOVE_INHABITANT_ROUTE_NAME = 'remove_inhabitant';
    const BUY_ROOM_ROUTE_NAME = 'add_room';

    /**
     *
     * @var BiographyParamGenerator
     */
    private $paramGenerator;

    /**
     *
     * @var CharacterRepository
     */
    private $charRepo;

    /**
     *
     * @var HouseRepository
     */
    private $houseRepo;

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    function __construct(BiographyParamGenerator $paramGenerator, CharacterRepository $charRepo, HouseRepository $houseRepo,
            EntityManagerInterface $eManager) {
        $this->paramGenerator = $paramGenerator;
        $this->charRepo = $charRepo;
        $this->houseRepo = $houseRepo;
        $this->eManager = $eManager;
    }

    /**
     * @Route("/build/{uuid}", name=HouseController::BUILD_HOUSE_ROUTE_NAME)
     */
    public function buildHouse(Request $request, $uuid, ConfigService $config) {
        $session = new RhunSession();
        /* @var $character Character */
        $character = $this->charRepo->find($session->getCharacterID());

        if (!$character->getLocation()->getHouseList()) {
            return $this->redirectToWorld($character);
        }

        $price = $config->getHouseConfig()->getHouseBuildPrice();

        if (!$character->getWallet()->checkPrice($price)) {
            $session->error('Das kannst du dir nicht leisten.');
            return $this->redirectToWorld($character);
        }

        $house = $this->houseRepo->findBy(['owner' => $character, 'location' => $character->getLocation()]);
        if ($house) {
            $session->error("Du hast hier bereits ein Haus.");
            return $this->redirectToWorld($character);
        }

        $this->createHouse($request, $character, $config->getHouseConfig());
        $character->getWallet()->addPrice($price->multiply(-1));
        $this->eManager->persist($character);
        $this->eManager->flush();
        return $this->redirectToWorld($character);
    }

    /**
     * @Route("/enter/{id}/{uuid}", name=HouseController::ENTER_HOUSE_ROUTE_NAME)
     */
    public function enterHouse($id, $uuid) {
        VarDumper::dump('test');
        $session = new RhunSession();
        /* @var $character Character */
        $character = $this->charRepo->find($session->getCharacterID());

        /* @var $house House */
        $house = $this->houseRepo->find($id);
        if (!$house) {
            $session->error('Es ist ein Fehler beim betreten des Hauses aufgetreten.');

            return $this->redirectToWorld($character);
        }

        if (!HouseService::characterMayEnter($house, $character)) {
            return $this->redirectToRoute(self::BURGLAR_HOUSE_ROUTE_NAME, ['uuid' => $uuid, 'id' => $id]);
        }
        $character->setLocation($house->getEntrance());
        $this->eManager->persist($character);
        $this->eManager->flush();
        return $this->redirectToWorld($character);
    }

    /**
     * @Route("/break/{id}/{uuid}", name=HouseController::BURGLAR_HOUSE_ROUTE_NAME)
     */
    public function breakIntoHouse($id, $uuid, ConfigService $config) {
        $session = new RhunSession();
        /* @var $character Character */
        $character = $this->charRepo->find($session->getCharacterID());

        /* @var $house House */
        $house = $this->houseRepo->find($id);
        if (!$house) {
            return $this->redirectToWorld($character);
        }

        $intrusion = new Intrusion($house->getAlarms(), $config);
        $success = $intrusion->isSuccess($character);
        if ($success === true) {
            $character->setLocation($house->getEntrance());
            $session->setBreakIn(true);
            $this->eManager->persist($character);
        } elseif ($success instanceof BurglarAlarm) {
            $character->setCurrentHP(0);
            $character->setDead(true);
            $this->eManager->persist($character);
            $session->log($success->getDeathMessage());
        }

        $this->eManager->flush();
        return $this->redirectToWorld($uuid);
    }

    /**
     * @Route("/show/{id}/{uuid}", name=HouseController::SHOW_HOUSE_ROUTE_NAME)
     */
    public function showHouseDescription($id, $uuid) {
        $house = $this->houseRepo->find($id);
        $session = new RhunSession();
        $character = $this->charRepo->find($session->getCharacterID());
        if (!$house) {
            $session->error('Dieses Haus gibt es scheinbar nicht.');
            return $this->redirectToWorld($character);
        }

        return $this->render($this->getSkinFile(), $this->paramGenerator->getHouseBioParams($house, $character));
    }

    /**
     * @Route("/edit/room/{uuid}", name=HouseController::EDIT_ROOM_ROUTE_NAME)
     */
    public function editRoom(Request $request, $uuid, LocationRepository $locRepo, FormatService $formatter) {
        /* @var $location Location */
        $location = $locRepo->find($request->get("location"));

        if ($location) {
            $location->setDescriptionSpring($request->get("description_spring"));
            $location->setDescriptionSummer($request->get("description_summer"));
            $location->setDescriptionFall($request->get("description_fall"));
            $location->setDescriptionWinter($request->get("description_winter"));
            $location->setColoredName($request->get("title"));
            $location->setName(strip_tags($formatter->parse($request->get("title"), true)));

            $this->eManager->persist($location);
            $this->eManager->flush();
        }
        $session = new RhunSession();
        return $this->redirectToWorld($this->charRepo->find($session->getCharacterId()));
    }

    /**
     * @Route("/edit/{id}/{uuid}", name=HouseController::EDIT_HOUSE_ROUTE_NAME)
     */
    public function editHouse(Request $request, $id, $uuid) {
        $manager = $this->getDoctrine()->getManager();
        /* @var $house House */
        $house = $this->houseRepo->find($id);

        $session = new RhunSession();
        $character = $this->charRepo->find($session->getCharacterID());
        if (!($house && $house->getOwner()->getId() == $character->getId())) {
            $session = new RhunSession();
            $session->error('Da ist wohl etwa schief gelaufen.');
            return $this->redirectToWorld($character);
        }

        $house->setDescription($request->get('text'));
        $house->setScript($request->get('script'));
        $house->setAvatar($request->get('avatar'));
        $house->setShowOwner(filter_var($request->get('showOwner'), FILTER_VALIDATE_BOOLEAN));

        $manager->persist($house);
        $manager->flush();
        return $this->redirectToWorld($character);
    }

    /**
     * @Route("/add/inhabitant/{uuid}", name="add_inhabitant")
     */
    public function addInhabitant(Request $request, $uuid) {
        $session = new RhunSession();
        /* @var $house House */
        $house = $this->houseRepo->find($request->get('houseId'));
        /* @var $character Character */
        $character = $this->charRepo->find($session->getCharacterID());

        if ($house->getOwner()->getId() != $character->getId()) {
            $session->error('Dieses Haus gehört dir nicht.');
            return $this->redirectToWorld($character);
        }

        $newInhabitant = $this->charRepo->findByName($request->get('target'));
        if (!$newInhabitant) {
            $session->error('Diesen Charakter gibt es nicht.');

            return $this->redirectToWorld($character);
        }

        $price = HouseService::getPriceKey($house);

        if (!HouseService::payPrice($price, $house, $character)) {
            $session->error('Dies kannst du dir nicht leisten.');
            return $this->redirectToWorld($character);
        }

        $house->addInhabitant($newInhabitant, $house);
        $this->eManager->persist($house);
        $this->eManager->persist($character);
        $this->eManager->flush();

        $this->sendSystemPN('Schlüssel bekommen', $character->getName() . ' hat dir einen Schlüssel zum Haus ' . $house->getTitle() . ' gegeben.', $newInhabitant);

        return $this->redirectToWorld($character);
    }

    /**
     * @Route("/remove/inhabitant/{uuid}", name=HouseController::REMOVE_INHABITANT_ROUTE_NAME)
     */
    public function removeInhabitant(Request $request, $uuid) {
        $session = new RhunSession();
        /* @var $house House */
        $house = $this->houseRepo->find($request->get('houseId'));
        $character = $this->charRepo->find($session->getCharacterID());

        if ($house->getOwner()->getId() != $character->getId()) {
            $session->error('Dieses Haus gehört dir nicht.');
            return $this->redirectToWorld($character);
        }

        $inhabitant = $this->charRepo->find($request->get('char'));
        if (!$inhabitant) {
            $session->error('Diesen Charakter gibt es nicht.');
            return $this->redirectToWorld($character);
        }

        $house->removeInhabitant($inhabitant);
        $this->eManager->persist($house);
        $this->eManager->flush();

        return $this->redirectToWorld($character);
    }

    /**
     * @Route("/add/room/{uuid}", name=HouseController::BUY_ROOM_ROUTE_NAME)
     */
    public function buildRoom(Request $request, $uuid, ConfigService $config) {
        $session = new RhunSession();
        $character = $this->charRepo->find($session->getCharacterID());

        $house = $this->houseRepo->find($request->get('houseId'));

        $price = HouseService::getBuyRoomPrice($house, $config->getHouseConfig());

        if ($house->getOwner()->getId() != $character->getId()) {
            return $this->redirectToWorld($character);
        }

        if (!HouseService::payPrice($price, $house, $character)) {
            $session->error('Dies kannst du dir nicht leisten.');
            return $this->redirectToWorld($character);
        }

        $houseService = new HouseService();
        $houseService->buyRoom($house, $request->get("type"));

        $this->eManager->persist($character);
        $this->eManager->persist($house);
        $this->eManager->flush();

        return $this->redirectToWorld($character);
    }

    /**
     * @Route("add/savings/{uuid}")
     */
    public function addSavings(Request $request, $uuid) {
        $session = new RhunSession();
        $character = $this->charRepo->find($session->getCharacterID());

        $house = $this->houseRepo->findByLocation($character->getLocation());
        $houseFunctions = $house->getHouseFunctions();
        if (!$houseFunctions->getSavingsActive()) {
            $session->error('Du hast keine Spardose.');
            return $this->redirectToWorld($character);
        }

        $amountPlatin = $request->get('platin');
        $amountGems = $request->get('gems');

        if ($amountPlatin > $character->getWallet()->getPlatin()) {
            $amountPlatin = $character->getWallet()->getPlatin();
        } elseif ($amountPlatin < 0) {
            $amountPlatin = 0;
        }
        if ($amountGems > $character->getWallet()->getGems()) {
            $amountGems = $character->getWallet()->getGems();
        } elseif ($amountGems < 0) {
            $amountGems = 0;
        }

        $houseFunctions->setSavingsPlatin($houseFunctions->getSavingsPlatin() + $amountPlatin);
        $houseFunctions->setSavingsGems($houseFunctions->getSavingsGems() + $amountGems);

        $character->getWallet()->addPrice(new Price(0, -1 * $amountPlatin, -1 * $amountGems));

        $this->eManager->persist($houseFunctions);
        $this->eManager->persist($character);
        $this->eManager->flush();

        return $this->redirectToWorld($character);
    }

    private function createHouse(Request $request, Character $character, HouseConfig $config) {
        $houseService = new HouseService();
        $areaRepo = $this->getDoctrine()->getRepository('App:Area');
        $house = $houseService->createNewHouse($request->get("title"), '', $character->getLocation(), $character, $areaRepo->find($config->getHouseAreaId()));

        $this->eManager->persist($house);
        $this->eManager->flush();

        $houseService->createHouseNavs($house, $this->eManager);
    }

}
