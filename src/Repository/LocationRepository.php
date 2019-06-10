<?php

namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Description of LocationRepository
 *
 * @author Draeius
 */
class LocationRepository extends ServiceEntityRepository {

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry) {
        parent::__construct($managerRegistry, Location::class);
    }

    public function getLocationsForRacesInAreas($areas) {
        $query = $this->createQueryBuilder('l')
                ->select('l')
                ->andWhere('l.area IN (:area)')
                ->andWhere('l.adult = false')
                ->setParameter('area', $areas)
                ->getQuery();
        return $query->execute();
    }

}
