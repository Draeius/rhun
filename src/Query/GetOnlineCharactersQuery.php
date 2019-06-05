<?php

namespace App\Query;

use App\Entity\Partial\CharacterNamePartial;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of GetOnlineCharactersQuery
 *
 * @author Draeius
 */
class GetOnlineCharactersQuery {

    const SQL = 'SELECT r.name, r.gender, r.coloredName, r.title, r.isInFront '.
                'FROM ('.
                    'SELECT '.
			'c.name,'.
                        'c.gender,'.
			'n.name as coloredName,'.
                        'n.isActivated as nActivated,'.
                        't.title,'.
                        't.isInFront,'.
                        't.isActivated as tActivated '.
                    'FROM characters c LEFT JOIN character_names n ON c.id = n.owner_id LEFT JOIN character_titles t ON c.id = t.owner_id '.
                    'WHERE c.online = 1) r '.
                'WHERE '.
                    '(r.coloredName IS NULL OR r.nActivated = 1)'.
                    'AND (r.title IS NULL OR r.tActivated = 1)';

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
        $q = $conn->prepare(self::SQL);
        $q->execute();
        $result = [];
        while ($data = $q->fetch()) {
            $result[] = CharacterNamePartial::FROM_DATA($data);
        }
        return $result;
    }

}
