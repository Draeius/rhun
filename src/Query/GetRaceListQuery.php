<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Query;

use App\Entity\Partial\RacePartial;
use App\Repository\AreaRepository;
use App\Util\SQLFileLoader;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of GetRaceListQuery
 *
 * @author Draeius
 */
class GetRaceListQuery {
    
    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    public function __construct(EntityManagerInterface $eManager) {
        $this->eManager = $eManager;
    }

    public function __invoke() {
        $conn = $this->eManager->getConnection();
        $cityQuery = $conn->prepare(SQLFileLoader::getSQLFileContent('allowedRacesCities'));
        $cityQuery->execute();
        $result = [];
        while ($data = $cityQuery->fetch()) {
            $cityName = AreaRepository::getColoredCityName($data['city']);
            $result[$cityName] = [];
            $racesQuery = $conn->prepare(SQLFileLoader::getSQLFileContent('allowedRacesByCity'));
            $racesQuery->bindParam(1, $data['city']);
            $racesQuery->execute();
            while ($race = $racesQuery->fetch()) {
                $result[$cityName][] = RacePartial::fromData($race);
            }
        }
        return $result;
    }

}
