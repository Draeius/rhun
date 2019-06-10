<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

use App\Doctrine\UuidEncoder;
use App\Entity\Location\WorldLocation;
use App\Repository\Traits\RepositoryUuidFinderTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Description of WorldLocationRepository
 *
 * @author Draeius
 */
class WorldLocationRepository extends ServiceEntityRepository {

    use RepositoryUuidFinderTrait;
    
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry) {
        parent::__construct($managerRegistry, WorldLocation::class);
        $this->uuidEncoder = new UuidEncoder();
    }

}
