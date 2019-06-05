<?php

namespace App\Query;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of GetNumberOfCharactersQuery
 *
 * @author Matthias
 */
class GetNumberOfCharactersQuery {

    const SQL = 'SELECT COUNT(c.id) as count '
            . 'FROM characters c '
            . 'WHERE c.account_id = ?';

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    public function __construct(EntityManagerInterface $eManager) {
        $this->eManager = $eManager;
    }

    public function __invoke(User $user) {
        $conn = $this->eManager->getConnection();
        $q = $conn->prepare(self::SQL);
        $q->bindParam(1, $user->getId());
        $q->execute();
        $data = $q->fetch();
        return $data['count'];
    }

}
