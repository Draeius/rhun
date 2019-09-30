<?php

namespace App\Repository;

use App\Entity\Location;
use App\Entity\Monster;
use App\Entity\Encounter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * 
 *
 * @author Draeius
 */
class MonsterRepository extends ServiceEntityRepository {

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry) {
        parent::__construct($managerRegistry, Monster::class);
    }

    public function findByLocation(Location $location) {
        if (!$location->getFighting()) {
            return null;
        }
        $query = $this->createQueryBuilder('m')
                ->join('m.occurances', 'o')
                ->join('o.location', 'l')
                ->where('l.id = ?')
                ->getQuery();
        return $query->execute([$location]);
    }

    public function findRandomByLocation(Location $location): ?Encounter {
        $occurances = $this->findByLocation($location);
        if (!$occurances) {
            return null;
        }
        $max = count($occurances);
        $rand = round(mt_rand() * $max);
        return $occurances[$rand];
    }

}
