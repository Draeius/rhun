<?php

namespace App\Query;

use App\Entity\Partial\CharacterNamePartial;
use App\Entity\Partial\Interfaces\CharacterNameInterface;
use App\Query\TooManyRowsException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Fragt nach dem Namen des neuesten Charakters in der Datenbank.
 *
 * @author Draeius
 */
class GetNewestCharacterNameQuery {

    const SQL = 'SELECT r.name, r.gender, r.coloredName, r.title, r.isInFront ' .
            'FROM (' .
            'SELECT ' .
            'c.name,' .
            'c.gender,' .
            'n.name as coloredName,' .
            'n.isActivated as nActivated,' .
            't.title,' .
            't.isInFront,' .
            't.isActivated as tActivated ' .
            'FROM characters c LEFT JOIN character_names n ON c.id = n.owner_id LEFT JOIN character_titles t ON c.id = t.owner_id ' .
            'WHERE c.isNewest = 1) r ' .
            'WHERE ' .
            '(r.coloredName IS NULL OR r.nActivated = 1)' .
            'AND (r.title IS NULL OR r.tActivated = 1)';

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
        $q = $conn->prepare(self::SQL);
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
