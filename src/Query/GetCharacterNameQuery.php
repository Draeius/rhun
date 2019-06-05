<?php

namespace App\Query;

use App\Entity\Partial\CharacterNamePartial;
use App\Entity\Partial\Interfaces\CharacterNameInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of FindNewestCharacter
 *
 * @author Draeius
 */
class GetCharacterNameQuery {

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
                    'WHERE c.id = ?) r '.
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
    
    public function __invoke(int $id): CharacterNameInterface {
        $conn = $this->eManager->getConnection();
        $q = $conn->prepare(self::SQL);
        $q->bindParam(1, $id);
        $q->execute();
        $data = $q->fetch();
        if(!$data){
            throw new EntityNotFoundException('Der Charakter mit der Id ' . $id . ' konnte nicht gefunden werden.');
        }
        return CharacterNamePartial::FROM_DATA($data);
    }

}
