<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Query;

/**
 * Description of GetPostCountQuery
 *
 * @author Draeius
 */
class GetPostCountQuery {

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    public function __construct(EntityManagerInterface $eManager) {
        $this->eManager = $eManager;
    }

    public function __invoke() {
        $conn = $this->eManager->getConnection();
        $q = $conn->prepare(SQLFileLoader::getSQLFileContent('postCount'));
        $q->execute();
        return $q->fetch()['count'];
    }

}
