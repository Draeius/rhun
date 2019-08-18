<?php

namespace App\Repository;

use App\Entity\Items\ArmorTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Description of ArmorRepository
 *
 * @author Draeius
 */
class ArmorTemplateRepository extends ServiceEntityRepository {

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry) {
        parent::__construct($managerRegistry, ArmorTemplate::class);
    }
}
