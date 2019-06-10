<?php

namespace App\Controller\Entity;

use App\Query\GetRacesByCityQuery;
use App\Service\FormatService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of RaceController
 *
 * @author Draeius
 */
class RaceController {

    const RACES_BY_CITY_ROUTE_NAME = 'races_for_city';

    /**
     * @Route("/race/by_city/{city}", name=RaceController::RACES_BY_CITY_ROUTE_NAME)
     */
    public function getRacesByCityAction(string $city, EntityManagerInterface $eManager, FormatService $formatter) {
        $query = new GetRacesByCityQuery($eManager);
        $races = $query($city);
        foreach ($races as $key => $race) {
            $races[$key]['name'] = $formatter->parse($race['name']);
        }
        return new JsonResponse($races);
    }

    public function showEditor() {
        
    }

}
