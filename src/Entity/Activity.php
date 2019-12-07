<?php

namespace App\Entity;

use App\Entity\Traits\EntityColoredNameTrait;
use App\Entity\Traits\EntityIdTrait;
use App\Util\Option\OptionPicker;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Symfony\Flex\Options;

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
     * @var Options[]
     * @OneToMany(targetEntity="App\Entity\Option\Option", mappedBy="activity")
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

    public function prepareOptions(): OptionPicker {
        $optionPicker = new OptionPicker();
        foreach ($this->options as $option) {
            $optionPicker->addOption($option);
        }
        return $optionPicker;
    }

}
