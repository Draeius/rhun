<?php

namespace App\Service;

/**
 *
 * @author Draeius
 */
abstract class TextRewardCalculator extends RewardCalculator {

    protected function calcReward(string $text, bool $increasePostCounter): Reward {
        $length = count(explode(' ', $text));

        if ($length < $this->config->getPostRewardMinWordCount()) {
            $length = 0;
        }
        if ($length > $this->config->getPostRewardMaxWordCount()) {
            $length = $this->config->getPostRewardMaxWordCount();
        }

        $price = $this->config->getPostReward();
        $multiplier = floor($length / $this->config->getPostRewardWordCount());
        $price->multiply($multiplier);

        $reward = new Reward();
        $reward->setPrice($price);
        if ($increasePostCounter) {
            $reward->setIncreasePostCounter($multiplier);
        }
        return $reward;
    }

}
