<?php

namespace App\Repository;

use App\Entity\GuildInvitation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Description of GuildInvitationRepository
 *
 * @author Draeius
 */
class GuildInvitationRepository extends ServiceEntityRepository {

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry) {
        parent::__construct($managerRegistry, GuildInvitation::class);
    }

}
