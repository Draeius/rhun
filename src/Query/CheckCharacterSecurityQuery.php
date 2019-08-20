<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Query;

/**
 * Description of CheckCharacterSecurityQuery
 *
 * @author Draeius
 */
class CheckCharacterSecurityQuery {

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    public function __construct(EntityManagerInterface $eManager) {
        $this->eManager = $eManager;
    }

    public function __invoke(int $characterId, int $accountId): bool {
        $conn = $this->eManager->getConnection();
        $q = $conn->prepare(SQLFileLoader::getSQLFileContent('checkAccountSecurity'));
        $q->bindParam(1, $characterId);
        $q->bindParam(2, $accountId);
        $q->execute();
        $data = $q->fetch();
        if (!$data) {
            return false;
        }
        return $data['result'];
    }

}
