<?php

namespace App\Entity;

use App\Option\Option;
use App\Option\OptionPicker;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use App\Entity\Location\LocationEntity;

/**
 * Description of Activity
 *
 * @author Draeius
 * @Entity
 * @Table(name="activities")
 */
class Activity {

    /**
     * @var int 
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     *
     * @var string
     * @Column(type="text", length=64)
     */
    protected $navLabel;

    /**
     * @var LocationEntity 
     * @ManyToOne(targetEntity="LocationEntity")
     * @JoinColumn(name="location_id", referencedColumnName="id")
     */
    protected $location;

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

    public function getId() {
        return $this->id;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getStaminaDrain() {
        return $this->staminaDrain;
    }

    public function getOptions() {
        return $this->options;
    }

    public function getNavLabel() {
        return $this->navLabel;
    }

    public function setNavLabel($navLabel) {
        $this->navLabel = $navLabel;
    }

    public function setLocation(LocationEntity $location) {
        $this->location = $location;
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
        if(!is_array($optionArray)){
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
