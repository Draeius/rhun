<?php

namespace App\Query;

use App\Util\SQLFileLoader;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of GetTotalNumberOfPostsQuery
 *
 * @author Draeius
 */
class GetTotalNumberOfPostsQuery {

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    public function __construct(EntityManagerInterface $eManager) {
        $this->eManager = $eManager;
    }

    public function __invoke(int $userId) {
        $conn = $this->eManager->getConnection();
        $q = $conn->prepare(SQLFileLoader::getSQLFileContent('totalNumberOfPosts'));
        $q->bindParam(1, $userId);
        $q->execute();
        $data = $q->fetch();
        return $data['count'];
    }

}
