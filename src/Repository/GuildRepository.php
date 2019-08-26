<?php

namespace App\Repository;

use App\Entity\Guild;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Description of GuildRepository
 *
 * @author Draeius
 */
class GuildRepository extends ServiceEntityRepository {

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry) {
        parent::__construct($managerRegistry, Guild::class);
    }

}
