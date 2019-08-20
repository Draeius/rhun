<?php

namespace App\Query;

use App\Annotation\Security;
use App\Util\SQLFileLoader;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of CheckAccountSecurityQuery
 *
 * @author Draeius
 */
class CheckAccountSecurityQuery {

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    public function __construct(EntityManagerInterface $eManager) {
        $this->eManager = $eManager;
    }

    public function __invoke(int $accountId, Security $annotation): bool {
        $conn = $this->eManager->getConnection();
        $q = $conn->prepare(SQLFileLoader::getSQLFileContent('checkAccountSecurity'));
        $q->bindParam(1, $annotation->reviewPosts);
        $q->bindParam(2, $annotation->editWorld);
        $q->bindParam(3, $annotation->writeMotd);
        $q->bindParam(4, $annotation->editItems);
        $q->bindParam(5, $annotation->editMonster);
        $q->bindParam(6, $accountId);
        $q->execute();
        $data = $q->fetch();
        if (!$data) {
            return false;
        }
        return $data['result'];
    }

}
