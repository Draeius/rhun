<?php

namespace App\Repository;

use App\Doctrine\UuidEncoder;
use App\Entity\Guild;
use App\Entity\House;
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

    public function findByHouse(House $house) {
        $query = $this->getEntityManager()->createQuery(
                        'SELECT n FROM App:Navigation n WHERE n.location IN (SELECT l.id FROM App:House h JOIN h.rooms l '
                        . 'WHERE h.id = :id)')
                ->setParameter('id', $house->getId());
        return $query->execute();
    }

    public function findByGuild(Guild $guild) {
        $query = $this->getEntityManager()->createQuery(
                        'SELECT n FROM App:Navigation n WHERE n.location IN (SELECT l.id FROM App:Guild g JOIN g.guildHall gh JOIN gh.locations l '
                        . 'WHERE g.id = :id)')
                ->setParameter('id', $guild->getId());
        return $query->execute();
    }

}
