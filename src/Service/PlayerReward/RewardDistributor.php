<?php

namespace App\Service\PlayerReward;

use App\Entity\Character;
use App\Entity\RhunEntity;
use App\Util\Config\RolePlayConfig;

/**
 *
 * @author Draeius
 */
class RewardDistributor {

    /**
     *
     * @var RolePlayConfig
     */
    private $config;

    public function __construct(RolePlayConfig $config) {
        $this->config = $config;
    }

    private function getReward(RhunEntity $entity): Price {
        $calculator = RewardCalculator::FACTORY($entity, $this->config);
        return $calculator->getRewardFor($entity);
    }

    /**
     * Hand out the Reward for the given Entity
     * @param RhunEntity $entity
     * @param Character $char
     */
    public function handOutReward(RhunEntity $entity, Character $char) {
        $reward = $this->getReward($entity);
        $char->getWallet()->addPrice($reward->getPrice());
        $char->setPostCounter($char->getPostCounter() + $reward->getIncreasePostCounter());
    }

    /**
     * Revokes the Reward for the given Entity
     * @param RhunEntity $entity
     * @param Character $char
     */
    public function revokeReward(RhunEntity $entity, Character $char) {
        $reward = $this->getReward($entity);
        $char->getWallet()->subtractPrice($reward->getPrice());
        $char->setPostCounter($char->getPostCounter() - $reward->getIncreasePostCounter());
    }

}
