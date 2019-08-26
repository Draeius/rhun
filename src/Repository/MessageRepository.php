<?php

namespace App\Repository;

use App\Entity\Character;
use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Description of MessageRepository
 *
 * @author Draeius
 */
class MessageRepository extends ServiceEntityRepository {

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry) {
        parent::__construct($managerRegistry, Message::class);
    }

    public function findNewMessages(Character $character) {
        $criteria = Criteria::create()
                ->where(Criteria::expr()
                        ->eq('addressee', $character))
                ->andWhere(Criteria::expr()
                ->eq('read', FALSE));
        $result = $this->matching($criteria);
        return $result->toArray();
    }

}
