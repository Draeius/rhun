<?php

namespace App\Service;

use App\Entity\Book;
use App\Entity\RhunEntity;

/**
 *
 * @author Draeius
 */
class BookRewardCalculator extends TextRewardCalculator {

    public function getRewardFor(RhunEntity $entity) {
        if ($entity instanceof Book) {
            return $this->calcReward($entity->getContent(), false);
        }
        throw new Exception('Entity is not of type Book.');
    }

}
