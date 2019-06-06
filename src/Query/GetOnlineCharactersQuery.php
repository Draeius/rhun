<?php

namespace App\Query;

use App\Entity\Partial\CharacterNamePartial;
use App\Util\SQLFileLoader;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of GetOnlineCharactersQuery
 *
 * @author Draeius
 */
class GetOnlineCharactersQuery {

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    public function __construct(EntityManagerInterface $eManager) {
        $this->eManager = $eManager;
    }

    public function __invoke() {
        $conn = $this->eManager->getConnection();
        $q = $conn->prepare(SQLFileLoader::getSQLFileContent('onlineCharacters'));
        $q->execute();
        $result = [];
        while ($data = $q->fetch()) {
            $result[] = CharacterNamePartial::FROM_DATA($data);
        }
        return $result;
    }

}
