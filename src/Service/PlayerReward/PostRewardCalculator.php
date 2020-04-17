<?php

namespace App\Service\PlayerReward;

use App\Entity\Post;
use App\Entity\RhunEntity;
use Exception;

/**
 *
 * @author Draeius
 */
class PostRewardCalculator extends TextRewardCalculator {

    public function getRewardFor(RhunEntity $entity): Reward {
        if (!($entity instanceof Post)) {
            throw new Exception('Entity is not of type Post.');
        }
        if (in_array($entity->getId(), $this->config->getExcludeFromReward())) {
            return new Reward();
        }
        return $this->calcReward($entity->getContent(), true);
    }

}
