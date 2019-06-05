<?php

namespace App\Query;

use App\Entity\Partial\CharacterListPartial;
use Doctrine\ORM\EntityManagerInterface;


/**
 * Description of GetCharacterTotalList
 *
 * @author Draeius
 */
class GetCharacterTotalListQuery {
    const SQL = 'SELECT x.name, x.gender, x.coloredName, x.title, x.isInFront, x.guildName, x.guildTag, x.raceName, x.level, x.locationTitle, x.dead, x.lastActive, x.online '.
                'FROM ('.
                    'SELECT '.
			'c.name,'.
                        'c.gender,'.
                        'c.level,'.
                        'c.dead,'.
                        'c.lastActive,'.
                        'c.online,'.
			'n.name as coloredName,'.
                        'n.isActivated as nActivated,'.
                        't.title,'.
                        't.isInFront,'.
                        't.isActivated as tActivated,' .
                        'g.name as guildName,'.
                        'g.tag as guildTag,'.
                        'r.name as raceName,'.
                        'l.title as locationTitle '.
                    'FROM characters c '.
                        'LEFT JOIN character_names n ON c.id = n.owner_id '.
                        'LEFT JOIN character_titles t ON c.id = t.owner_id '.
                        'LEFT JOIN guilds g ON c.guild_id = g.id '.
                        'LEFT JOIN races r ON c.race_id = r.id '.
                        'LEFT JOIN location l ON c.location_id = l.id '.
                    ') x '.
                'WHERE '.
                    'x.coloredName IS NULL OR x.nActivated = 1 '.
                    'AND (x.title IS NULL OR x.tActivated = 1)';
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
        $q = $conn->prepare(self::SQL);
        $q->execute();
        $result = [];
        while ($data = $q->fetch()) {
            $result[] = CharacterListPartial::FROM_DATA($data);
        }
        return $result;
    }

}
