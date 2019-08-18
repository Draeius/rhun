<?php

namespace App\Repository;

use App\Entity\Items\WeaponTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Description of WeaponTemplateRepository
 *
 * @author Draeius
 */
class WeaponTemplateRepository extends ServiceEntityRepository {

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry) {
        parent::__construct($managerRegistry, WeaponTemplate::class);
    }
}