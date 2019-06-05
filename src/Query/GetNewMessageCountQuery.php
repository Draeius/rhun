<?php

namespace App\Query;

/**
 * Description of GetNewMessageCountQuery
 *
 * @author Draeius
 */
class GetNewMessageCountQuery {

    const SQL = 'SELECT COUNT(c.id) as count '
            . 'FROM characters c JOIN messages m ON m.addressee_id = c.id '
            . 'WHERE c.account_id = ? AND m.is_old = 0';

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    public function __construct(EntityManagerInterface $eManager) {
        $this->eManager = $eManager;
    }

    public function __invoke(User $user) {
        $conn = $this->eManager->getConnection();
        $q = $conn->prepare(self::SQL);
        $q->bindParam(1, $user->getId());
        $q->execute();
        $data = $q->fetch();
        return $data['count'];
    }

}
