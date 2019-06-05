<?php

namespace App\Repository\Traits;

use Doctrine\ORM\Mapping\Entity;

/**
 * Ein Trait, welches es ermöglicht, nach dem neuesten eintrag einer Tabelle zu suchen.
 * Sofern dieses Entity das Trait EntityCreateTrait hat.
 *
 * @author Draeius
 */
trait RepositoryCreatedFinder {

    /**
     * Findet die neuesten $limit Einträge in der Tabelle dieses Repositories.
     * 
     * @param int $limit
     * @return Entity|null
     */
    public function findNewest(int $limit = 1) {
        return $this->findBy([], [
                    'created_at' => 'DESC'
                        ], $limit);
    }

}
