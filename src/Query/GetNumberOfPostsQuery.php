<?php

namespace App\Query;

use App\Util\SQLFileLoader;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of GetNumberOfPostsQuery
 *
 * @author Draeius
 */
class GetNumberOfPostsQuery {

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    public function __construct(EntityManagerInterface $eManager) {
        $this->eManager = $eManager;
    }

    public function __invoke(int $charId) {
        $conn = $this->eManager->getConnection();
        $q = $conn->prepare(SQLFileLoader::getSQLFileContent('numberOfPosts'));
        $q->bindParam(1, $charId);
        $q->execute();
        $data = $q->fetch();
        return $data['count'];
    }

}
