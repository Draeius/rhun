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
        $race = new Race();
        $form = $this->createForm(PersistRaceForm::class, $race);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // ... save the meetup, redirect etc.
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
