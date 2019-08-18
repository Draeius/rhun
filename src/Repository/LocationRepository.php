<?php

namespace App\Repository;

use App\Doctrine\UuidEncoder;
use App\Entity\Location;
use App\Repository\Traits\RepositoryUuidFinderTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Description of LocationRepository
 *
 * @author Draeius
 */
class LocationRepository extends ServiceEntityRepository {

    use RepositoryUuidFinderTrait;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry) {
        parent::__construct($managerRegistry, Location::class);
        $this->uuidEncoder = new UuidEncoder();
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
