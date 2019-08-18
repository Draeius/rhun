<?php

namespace App\Query;

use App\Util\SQLFileLoader;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of GetIsPostAnsweredQuery
 *
 * @author Draeius
 */
class GetIsPostAnsweredQuery {

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    public function __construct(EntityManagerInterface $eManager) {
        $this->eManager = $eManager;
    }

    public function __invoke(int $accountId) {
        $conn = $this->eManager->getConnection();
        $q = $conn->prepare(SQLFileLoader::getSQLFileContent('isPostAnswered'));
        $q->bindParam(1, $accountId);
        $q->execute();
        $result = [];
        while ($data = $q->fetch()) {
            $result[$data['character_id']][] = $data;
        }
        return $result;
    }

}
