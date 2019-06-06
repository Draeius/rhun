<?php

namespace App\Query;

use App\Entity\User;
use App\Util\SQLFileLoader;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of GetNumberOfCharactersQuery
 *
 * @author Matthias
 */
class GetNumberOfCharactersQuery {

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
        $q = $conn->prepare(SQLFileLoader::getSQLFileContent('numberOfCharacters'));
        $q->bindParam(1, $user->getId());
        $q->execute();
        $data = $q->fetch();
        return $data['count'];
    }

}
