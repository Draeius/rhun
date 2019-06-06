<?php

namespace App\Query;

use App\Entity\Partial\CharacterNamePartial;
use App\Entity\Partial\Interfaces\CharacterNameInterface;
use App\Util\SQLFileLoader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Description of FindNewestCharacter
 *
 * @author Draeius
 */
class GetCharacterNameQuery {
    
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
        $q = $conn->prepare(SQLFileLoader::getSQLFileContent('getCharacterName'));
        $q->bindParam(1, $id);
        $q->execute();
        $data = $q->fetch();
        if(!$data){
            throw new EntityNotFoundException('Der Charakter mit der Id ' . $id . ' konnte nicht gefunden werden.');
        }
        return CharacterNamePartial::FROM_DATA($data);
    }

}
