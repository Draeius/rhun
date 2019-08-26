<?php

namespace App\Repository;

use App\Entity\Character;
use App\Entity\House;
use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Description of HouseRepository
 *
 * @author Draeius
 */
class HouseRepository extends ServiceEntityRepository {

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry) {
        parent::__construct($managerRegistry, House::class);
    }

    public function findByLocation(Location $location) {
        $query = $this->createQueryBuilder("h")
                ->where(':location MEMBER OF h.rooms')
                ->setParameters(array('location' => $location))
                ->getQuery();
        return $query->execute()[0];
    }

    public function findByInhabitant(Character $inhabitant) {
        $qb = $this->createQueryBuilder("h")
                ->where(':inhabitant MEMBER OF h.inhabitants')
                ->setParameters(array('inhabitant' => $inhabitant));
        return $qb->getQuery()->getResult();
    }

}
