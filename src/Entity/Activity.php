<?php

namespace App\Entity;

use App\Entity\Traits\EntityColoredNameTrait;
use App\Entity\Traits\EntityIdTrait;
use App\Option\Option;
use App\Option\OptionPicker;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of Activity
 *
 * @author Draeius
 * @Entity
 * @Table(name="activities")
 * @HasLifecycleCallbacks
 */
class Activity extends LocationBasedEntity {

    use EntityIdTrait;
    use EntityColoredNameTrait;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $staminaDrain;

    /**
     *
     * @var string
     * @Column(type="text")
     */
    protected $options;

    public function getStaminaDrain() {
        return $this->staminaDrain;
    }

    public function getOptions() {
        return $this->options;
    }

    public function setStaminaDrain($staminaDrain) {
        $this->staminaDrain = $staminaDrain;
    }

    public function setOptions($options) {
        $this->options = $options;
    }

    public function prepareOptions(EntityManager $manager, $uuid): OptionPicker {
        $optionPicker = new OptionPicker();
        $optionArray = json_decode($this->options, true);
        if (!is_array($optionArray)) {
            return $optionPicker;
        }
        foreach ($optionArray as $params) {
            $option = Option::FACTORY($manager, $uuid, $params);
            if ($option) {
                $optionPicker->addOption($option);
            }
        }
        return $optionPicker;
    }

}
