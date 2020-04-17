<?php

namespace App\Service\PlayerReward;

use App\Entity\Book;
use App\Entity\Post;
use App\Entity\RhunEntity;
use App\Util\Config\RolePlayConfig;
use App\Util\Price;

/**
 * Description of RewardCalculator
 *
 * @author Draeius
 */
abstract class RewardCalculator {
    
    /**
     *
     * @var RolePlayConfig
     */
    protected $config;

    public function __construct(RolePlayConfig $config) {
        $this->config = $config;
    }

    public abstract function getRewardFor(RhunEntity $entity);

    /**
     * Creates a RewardCalculator for the given Entity
     * @param RhunEntity $entity
     * @param RolePlayConfig $config
     */
    public static function FACTORY(RhunEntity $entity, RolePlayConfig $config) {
        if ($entity instanceof Post) {
            return new PostRewardCalculator($config);
        } else if ($entity instanceof Book) {
            return new PostRewardCalculator($config);
        }
        throw new Exception('No RewardCalculator found for Entity of type ' . get_class($entity));
    }

}
