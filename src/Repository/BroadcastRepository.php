<?php

namespace App\Repository;

use App\Entity\Broadcast;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Description of BroadcastRepository
 *
 * @author Draeius
 */
class BroadcastRepository extends ServiceEntityRepository {

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry) {
        parent::__construct($managerRegistry, Broadcast::class);
    }

    public function findNewestBroadcast(bool $isCoding): ?Broadcast {
        return $this->findOneBy(['newest' => true, 'codingBroadcast' => $isCoding]);
    }

    public function findBroadcast(int $offset, bool $isCoding): ?Broadcast {
        
    }

}
