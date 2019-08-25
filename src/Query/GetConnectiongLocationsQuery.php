<?php

namespace App\Query;

use App\Entity\Partial\LocationNamePartial;
use App\Util\SQLFileLoader;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of GetConnectiongLocationsQuery
 *
 * @author Draeius
 */
class GetConnectiongLocationsQuery {

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    public function __construct(EntityManagerInterface $eManager) {
        $this->eManager = $eManager;
    }

    public function __invoke(string $locationId) {
        $conn = $this->eManager->getConnection();
        $q = $conn->prepare(SQLFileLoader::getSQLFileContent('connectingLocationSQL'));
        $q->bindParam(1, $locationId);
        $q->execute();
        $result = [];
        while ($data = $q->fetch()) {
            $result[] = LocationNamePartial::fromData($data);
        }
        return $result;
    }

}
