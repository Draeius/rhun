<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Query;

use App\Entity\Partial\RacePartial;
use App\Repository\AreaRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of GetRaceListQuery
 *
 * @author Matthias
 */
class GetRaceListQuery {

    const SQL_CITIES = 'SELECT r.city FROM races r WHERE r.allowed=1 GROUP BY r.city';
    const SQL_RACES = 'SELECT r.name, r.description, r.city FROM races r WHERE r.allowed=1 AND r.city = ?';

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
        $cityQuery = $conn->prepare(self::SQL_CITIES);
        $cityQuery->execute();
        $result = [];
        while ($data = $cityQuery->fetch()) {
            $result[$data['city']] = [];
            $racesQuery = $conn->prepare(self::SQL_RACES);
            $racesQuery->bindParam(1, $data['city']);
            $racesQuery->execute();
            AreaRepository::getColoredCityName($data['city']);
            while ($race = $racesQuery->fetch()) {
                $result[$cityName][] = RacePartial::fromData($race);
            }
        }
        return $result;
    }

}
