<?php

namespace App\Query;

use App\Entity\Partial\CharacterNamePartial;
use App\Entity\Partial\Interfaces\CharacterNameInterface;
use App\Query\TooManyRowsException;
use App\Util\SQLFileLoader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Fragt nach dem Namen des neuesten Charakters in der Datenbank.
 *
 * @author Draeius
 */
class GetNewestCharacterNameQuery {

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    public function __construct(EntityManagerInterface $eManager) {
        $this->eManager = $eManager;
    }

    public function __invoke(): CharacterNameInterface {
        $conn = $this->eManager->getConnection();
        $q = $conn->prepare(SQLFileLoader::getSQLFileContent('newestCharacterName'));
        $q->execute();
        if ($q->rowCount() > 1) {
            throw new TooManyRowsException('Das Feld isNewest darf nur einen true Wert enthalten. Es wurden ' . $q->rowCount() . ' gefunden.');
        }
        $data = $q->fetch();
        if (!$data) {
            throw new EntityNotFoundException('Es wurde kein Charakter gefunden, der als der Neueste markiert wurde.');
        }
        return CharacterNamePartial::FROM_DATA($data);
    }

}
