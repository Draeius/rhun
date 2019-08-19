<?php

namespace App\Util\Config;

use App\Util\Price;

/**
 * Description of RolePlayConfig
 *
 * @author Matthias
 */
class RolePlayConfig extends Config {

    public function getRpPointRewardMultiplier(): int {
        $data = $this->getData('post');
        $reward = $this->getData('reward', $data);

        return $this->getData('point_multiplier', $reward);
    }

    public function getWordFilter(): bool {
        $data = $this->getData('post');

        return $this->getData('word_filter', $data);
    }

    public function getPostReward(): Price {
        $data = $this->getData('post');
        $reward = $this->getData('reward', $data);

        return $this->getPrice('monetary', $reward);
    }

    public function getPostRewardWordCount(): float {
        $data = $this->getData('post');
        $reward = $this->getData('reward', $data);
        $monetary = $this->getData('monetary', $reward);

        return $this->getData('word_count', $monetary);
    }

    public function getPostRewardMinWordCount(): int {
        $data = $this->getData('post');
        $reward = $this->getData('reward', $data);

        return $this->getData('min_word_count', $reward);
    }

    public function getPostRewardMaxWordCount(): int {
        $data = $this->getData('post');
        $reward = $this->getData('reward', $data);

        return $this->getData('max_word_count', $reward);
    }

    public function getColoredNamePrice(): Price {
        $data = $this->getData('colored_name');

        return $this->getPrice('price', $data);
    }

    public function getColoredTitlePrice(): Price {
        $data = $this->getData('colored_title');

        return $this->getPrice('price', $data);
    }
    
    public function getStatResetPrice(): Price {
        $data = $this->getData('stat_reset');

        return $this->getPrice('price', $data);
    }

}
