<?php

namespace App\Controller;

use App\Controller\BasicController;
use App\Entity\Area;
use App\Entity\ArmorType;
use App\Entity\Attribute;
use App\Entity\Character;
use App\Entity\Items\ArmorTemplate;
use App\Entity\Items\WeaponTemplate;
use App\Entity\Location;
use App\Entity\Navigation;
use App\Entity\Race;
use App\Entity\WeaponType;
use App\Repository\AreaRepository;
use App\Repository\ArmorTemplateRepository;
use App\Repository\LocationRepository;
use App\Repository\WeaponTemplateRepository;
use App\Service\ConfigService;
use App\Util\Fight\Fight;
use App\Util\Price;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Description of TestController
 *
 * @author Draeius
 */
class TestController extends BasicController {

    /**
     * @Route("/test")
     */
    public function test(Request $request, ConfigService $configService, Stopwatch $stopwatch) {

        $fight = new Fight();
        $fight->addFighter(new Character());

        VarDumper::dump($fight);
        VarDumper::dump($fight->serialize());
        VarDumper::dump(json_decode($fight->serialize()));

        return $this->render('test.html.twig');
    }

    /**
     * @Route("/popData")
     */
    public function populateData(Request $request, EntityManagerInterface $eManager, LocationRepository $locRepo, AreaRepository $areaRepo, WeaponTemplateRepository $wepRepo,
            ArmorTemplateRepository $armRepo) {
        $data = $this->getData();
        $this->populateAreas($data['areas'], $eManager);
        $eManager->flush();
        $this->populateLocations($data['location'], $eManager, $areaRepo);
        $eManager->flush();
        $this->populateItems($data['items'], $eManager);
        $eManager->flush();
        $this->populateRaces($data['races'], $locRepo, $wepRepo, $armRepo, $eManager);
        $eManager->flush();
        $this->populateNavs($data['navigation'], $locRepo, $eManager);
        $eManager->flush();

        return new JsonResponse('success');
    }

    private function getData(): array {
        $data = json_decode(file_get_contents('../SQL/data.json'), true);
        $resultSet = [];
        foreach ($data as $key => $entry) {
            if (array_key_exists('data', $entry)) {
                $resultSet[$entry['name']] = $entry['data'];
            }
        }
        return $resultSet;
    }

    private function populateNavs(array $dataSet, LocationRepository $locRepo, EntityManagerInterface $eManager) {
        foreach ($dataSet as $data) {
            if ($locRepo->find($data['location_id'])) {
                $nav = new Navigation();
                $nav->setName(htmlspecialchars(preg_replace('/`./', '', $data['label'])));
                $nav->setColoredName(htmlspecialchars($data['label']));
                $nav->setHref($data['href']);
                $nav->setNavbarIndex($data['navbarIndex']);
                $nav->setLocation($locRepo->find($data['location_id']));
                if ($data['target_location_id'] && $locRepo->find($data['target_location_id'])) {
                    $nav->setTargetLocation($locRepo->find($data['target_location_id']));
                }
                $nav->setPopup($data['popup']);
                $eManager->persist($nav);
            }
        }
    }

    private function populateLocations(array $dataSet, EntityManagerInterface $eManager, AreaRepository $areaRepo) {
        foreach ($dataSet as $data) {
            if (array_search($data['area_id'], [10, 11, 12, 13, 14, 15, 16, 17]) === false) {
                $loc = new Location();
                $loc->setAdult($data['adult']);
                $loc->setArea($areaRepo->find($data['area_id']));
                if (strpos($data['title'], '`') === false) {
                    $loc->setName(htmlspecialchars($data['title']));
                } else {
                    $loc->setName(htmlspecialchars(substr($data['title'], 2)));
                }
                $loc->setColoredName(htmlspecialchars($data['title']));
                $loc->setDescriptionSpring($data['descriptionSpring']);
                $loc->setDescriptionSummer($data['descriptionSummer']);
                $loc->setDescriptionFall($data['descriptionFall']);
                $loc->setDescriptionWinter($data['descriptionWinter']);
                $eManager->persist($loc);
            }
        }
    }

    private function populateRaces(array $dataSet, LocationRepository $locRepo, WeaponTemplateRepository $wepRepo,
            ArmorTemplateRepository $armRepo, EntityManagerInterface $eManager) {
        foreach ($dataSet as $data) {
            $race = new Race();
            $race->setCity($data['city']);
            $race->setName(htmlspecialchars(preg_replace('/`./', '', $data['name'])));
            $race->setColoredName(htmlspecialchars($data['name']));
            $race->setCity($data['city']);
            $race->setAllowed($data['allowed']);
            $race->setDescription($data['description']);
            $race->setLocation($locRepo->find($data['start_loc_id']));
            $race->setDeathLocation($locRepo->find($data['death_loc_id']));
            $race->setDefaultArmor($armRepo->find(1));
            $race->setDefaultWeapon($wepRepo->find(2));
            $eManager->persist($race);
        }
    }

    private function populateAreas(array $dataSet, EntityManagerInterface $eManager) {
        foreach ($dataSet as $areaData) {
            $area = new Area();
            $area->setCity($areaData['city']);
            $area->setName(substr($areaData['name'], 2));
            $area->setColoredName($areaData['name']);
            $area->setDeadAllowed($areaData['deadAllowed']);
            $area->setDescription($areaData['description'] ? $areaData['description'] : '');
            $area->setRaceAllowed(false);
            $eManager->persist($area);
        }
    }

    private function populateItems(array $dataSet, EntityManagerInterface $eManager) {
        $weapon = new WeaponTemplate();
        $weapon->setAttribute(Attribute::AGILITY);
        $weapon->setBaseDamage(5);
        $weapon->setPrice(new Price(100, 0, 0));
        $weapon->setColoredName('TestWaffe');
        $weapon->setDescription('Ein kleiner Test');
        $weapon->setLevel(1);
        $weapon->setMadeByPlayer(false);
        $weapon->setMinAttribute(10);
        $weapon->setName('TestWaffe');
        $weapon->setStaminaDrain(1);
        $weapon->setWeaponType(WeaponType::DAGGER);
        $eManager->persist($weapon);

        $armor = new ArmorTemplate();
        $armor->setArmorType(ArmorType::LIGHT);
        $armor->setAttribute(Attribute::AGILITY);
        $armor->setSecondAttribute(-1);
        $armor->setMinSecondAttr(0);
        $armor->setPrice(new Price(100, 0, 0));
        $armor->setColoredName('TestRüstung');
        $armor->setDescription('Ein kleiner Test');
        $armor->setLevel(1);
        $armor->setMadeByPlayer(false);
        $armor->setMinAttribute(10);
        $armor->setName('TestRüstung');
        $armor->setStaminaDrain(1);
        $eManager->persist($armor);
    }

}
