<?php

namespace App\Repository;

use App\Entity\Area;
use App\Entity\Race;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Description of RaceRepository
 *
 * @author Draeius
 */
class RaceRepository extends ServiceEntityRepository {

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry) {
        parent::__construct($managerRegistry, Race::class);
    }

    public function getRacesInArea(Area $area) {
        return $this->getRacesInCity($area->getCity());
    }

    public function getRacesInCity(string $area) {
        return $this->findBy(['city' => $area], ['name' => 'ASC']);
    }

}
