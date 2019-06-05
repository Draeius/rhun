<?php

namespace App\Controller;

use App\Controller\BasicController;
use App\Query\GetNewMessageCountQuery;
use App\Service\ConfigService;
use Doctrine\ORM\EntityManagerInterface;
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
    public function test(EntityManagerInterface $eManager, ConfigService $configService, Stopwatch $stopwatch) {
        $query = new GetNewMessageCountQuery($eManager);
        $query(705);
        return $this->render('base.html.twig');
    }

}
