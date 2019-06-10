<?php

namespace App\Controller;

use App\Controller\BasicController;
use App\Entity\Race;
use App\Form\PersistRaceForm;
use App\Service\ConfigService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Description of TestController
 *
 * @author Matthias
 */
class TestController extends BasicController {

    /**
     * @Route("/test")
     */
    public function test(Request $request, EntityManagerInterface $eManager, ConfigService $configService, Stopwatch $stopwatch) {
//        $area = new Area();
//        $area->setCity('seiya');
//        $area->setName('TestAreal');
//        $area->setColoredName('TestFarbigerName');
//        $area->setDeadAllowed(false);
//        $area->setDescription('Tolle Beschreibung');
//        $area->setRaceAllowed(true);
//        
//        $eManager->persist($area);
//        
//        $location = new Location();
//        $location->setAdult(false);
//        $location->setArea($area);
//        $location->setColoredName('Farbiger Name 1');
//        $location->setName('Name 1');
//        $location->setDescriptionSpring('Beschreibung 1');
//        $eManager->persist($location);
//        
//        $location2 = new Location();
//        $location2->setAdult(true);
//        $location2->setArea($area);
//        $location2->setColoredName('Farbiger Name 2');
//        $location2->setName('Name 2');
//        $location2->setDescriptionSpring('Beschreibung 2');
//        $eManager->persist($location2);
//        
//        $eManager->flush();


        $race = new Race();
        $form = $this->createForm(PersistRaceForm::class, $race);
        VarDumper::dump($race);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $eManager->persist($race);
            $eManager->flush();
        }

        return $this->render('test.html.twig', ['form' => $form->createView()]);
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

}
