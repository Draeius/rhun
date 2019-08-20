<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

use App\Entity\Job;
use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Description of JobRepository
 *
 * @author matth
 */
class JobRepository extends ServiceEntityRepository {

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry) {
        parent::__construct($managerRegistry, Job::class);
    }

    public function findByLocation(Location $location) {
        $qb = $this->createQueryBuilder("j")
                ->where(':location MEMBER OF j.locations')
                ->setParameters(array('location' => $location));
        return $qb->getQuery()->getResult();
    }

}
