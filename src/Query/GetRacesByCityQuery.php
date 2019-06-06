<?php

namespace App\Query;

use App\Util\SQLFileLoader;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of GetRacesByCityQuery
 *
 * @author Draeius
 */
class GetRacesByCityQuery {

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    public function __construct(EntityManagerInterface $eManager) {
        $this->eManager = $eManager;
    }

    public function __invoke(string $city) {
        $conn = $this->eManager->getConnection();
        $cityQuery = $conn->prepare(SQLFileLoader::getSQLFileContent('allowedRacesByCity'));
        $cityQuery->bindParam(1, $city);
        $cityQuery->execute();
        $result = [];
        while ($data = $cityQuery->fetch()) {
            $result[] = $data;
        }
        return $result;
    }

}
