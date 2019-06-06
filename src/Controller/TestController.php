<?php

namespace App\Controller;

use App\Controller\BasicController;
use App\Query\GetRaceListQuery;
use App\Service\ConfigService;
use Doctrine\ORM\EntityManagerInterface;
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
    public function test(EntityManagerInterface $eManager, ConfigService $configService, Stopwatch $stopwatch) {
        $query = new GetRaceListQuery($eManager);
        VarDumper::dump($query());
        return $this->render('base.html.twig');
    }

}
