<?php

namespace App\Controller;

use App\Controller\BasicController;
use App\Entity\Area;
use App\Entity\ArmorType;
use App\Entity\Attribute;
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
use App\Service\SkinService;
use App\Util\Price;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;
use function mb_convert_encoding;

/**
 * Description of TestController
 *
 * @author Draeius
 */
class TestController extends BasicController {

    /**
     * @Route("/test")
     */
    public function test(SkinService $skinService) {

        VarDumper::dump($skinService->getSkinList());

        return $this->render('test.html.twig');
    }

    /**
     * @Route("/updateDatabase")
     */
    public function updateDatabase(Request $request, KernelInterface $kernel, EntityManagerInterface $eManager, LocationRepository $locRepo,
            AreaRepository $areaRepo, WeaponTemplateRepository $wepRepo, ArmorTemplateRepository $armRepo) {
        $this->populateData($request, $eManager, $locRepo, $areaRepo, $wepRepo, $armRepo);

        return new Response($content);
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
        $file = mb_convert_encoding(file_get_contents('../SQL/data.json'), 'HTML-ENTITIES', "UTF-8");
        $data = json_decode($file, true);
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
                $loc->setColoredName($data['title']);
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
            $race->setColoredName($data['name']);
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
            $area->setColoredName($areaData['name']);
            $area->setDeadAllowed($areaData['deadAllowed']);
            $area->setDescription($areaData['description'] ? $areaData['description'] : '');
            $area->setRaceAllowed(array_search($areaData['city'], ['nelaris', 'manosse', 'pyra', 'lerentia', 'underworld'], true) !== false);
            $area->setRaceAllowed(array_search($area->getCity(), ['nelaris', 'manosse', 'pyra', 'lerentia', 'underworld'], true) !== false);
            $eManager->persist($area);
        }
    }

    private function populateItems(array $dataSet, EntityManagerInterface $eManager) {
        $damageRepo = $eManager->getRepository('App:DamageType');
        $weapon = new WeaponTemplate();
        $weapon->setAttribute(Attribute::DEXTERITY);
        $weapon->setDamage('1d8');
        $weapon->setPrice(new Price(100, 0, 0));
        $weapon->setColoredName('TestWaffe');
        $weapon->setDescription('Ein kleiner Test');
        $weapon->setMadeByPlayer(false);
        $weapon->setMinAttribute(10);
        $weapon->setWeaponType(WeaponType::DAGGER);
        $weapon->setDamageType($damageRepo->find(12));
        $eManager->persist($weapon);

        $armor = new ArmorTemplate();
        $armor->setArmorType(ArmorType::LIGHT);
        $armor->setAttribute(Attribute::DEXTERITY);
        $armor->setPrice(new Price(100, 0, 0));
        $armor->setColoredName('TestRüstung');
        $armor->setDescription('Ein kleiner Test');
        $armor->setMadeByPlayer(false);
        $armor->setMinAttribute(10);
        $armor->setDefense(15);
        $armor->setResistances([
            $damageRepo->find(6),
            $damageRepo->find(7)
        ]);
        $armor->setVulnerabilities([
            $damageRepo->find(8),
            $damageRepo->find(9)
        ]);
        $eManager->persist($armor);
    }

}
