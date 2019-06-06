<?php

namespace App\Query;

use App\Entity\User;
use App\Util\SQLFileLoader;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of GetNewMessageCountQuery
 *
 * @author Draeius
 */
class GetNewMessageCountQuery {

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
        $q = $conn->prepare(SQLFileLoader::getSQLFileContent('newMessageCount'));
        $userId = $user->getId();
        $q->bindParam(1, $userId);
        $q->execute();
        $data = $q->fetch();
        return $data['count'];
    }

}
