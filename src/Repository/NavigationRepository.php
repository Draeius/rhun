<?php

namespace App\Repository;

use App\Doctrine\UuidEncoder;
use App\Entity\Location;
use App\Entity\Navigation;
use App\Repository\Traits\RepositoryUuidFinderTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Description of NavigationRepository
 *
 * @author Draeius
 */
class NavigationRepository extends ServiceEntityRepository {

    use RepositoryUuidFinderTrait;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry) {
        parent::__construct($managerRegistry, Navigation::class);
        $this->uuidEncoder = new UuidEncoder();
    }

    public function findByLocation(Location $location) {
        return $this->findBy(['location' => $location], ['navbarIndex' => 'ASC']);
    }

}
