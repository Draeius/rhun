<?php

namespace App\Query;

use App\Entity\Partial\CharacterListPartial;
use App\Util\SQLFileLoader;
use Doctrine\ORM\EntityManagerInterface;


/**
 * Description of GetCharacterTotalList
 *
 * @author Draeius
 */
class GetCharacterTotalListQuery {

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    public function __construct(EntityManagerInterface $eManager) {
        $this->eManager = $eManager;
    }

    public function __invoke(): array {
        $conn = $this->eManager->getConnection();
        $q = $conn->prepare(SQLFileLoader::getSQLFileContent('characterTotalList'));
        $q->execute();
        $result = [];
        while ($data = $q->fetch()) {
            $result[] = CharacterListPartial::FROM_DATA($data);
        }
        return $result;
    }

}
