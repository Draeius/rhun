<?php

namespace App\Repository;

use App\Entity\Character;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Description of UserRepository
 *
 * @author Draeius
 */
class UserRepository extends ServiceEntityRepository {

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry) {
        parent::__construct($managerRegistry, User::class);
    }

    public function ownsCharacter(User $account, Character $char) {
        $charRepo = $this->getEntityManager()->getRepository('App:Character');
        if ($charRepo->findBy(['account' => $account, 'id' => $char->getId()])) {
            return true;
        }
        return false;
    }

}
