<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Description of PostRepository
 *
 * @author Draeius
 */
class PostRepository extends ServiceEntityRepository {

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry) {
        parent::__construct($managerRegistry, Post::class);
    }

    public function findByLocation(LocationEntity $location, $limit = 10, $page = 0) {
        $settings = $this->getEntityManager()->getRepository('App:ServerSettings')->find(1);
        $criteria = Criteria::create()
                ->where(Criteria::expr()
                        ->eq('location', $location))
                ->orderBy(array("creationDate" => "DESC"))
                ->setFirstResult($limit * $page)
                ->setMaxResults($limit);

        if ($settings->getUseMaskedBios()) {
            $criteria->andWhere(Criteria::expr()
                            ->gt('creationDate', $settings->getMaskedBallStart()));
        }

        $result = $this->matching($criteria);
        return array_reverse($result->getValues());
    }
}
