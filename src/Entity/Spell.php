<?php

namespace App\Entity;

use App\Entity\Traits\EntityColoredNameTrait;
use App\Option\Option;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of Spell
 *
 * @author Draeius
 * @Entity
 * @Table(name="spells")
 */
class Spell extends RhunEntity {

    use EntityColoredNameTrait;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $apCost;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $mpCost;

    /**
     *
     * @var string
     * @Column(type="text")
     */
    protected $options;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $icon;

    public function getOptions() {
        return $this->options;
    }

    public function getApCost() {
        return $this->apCost;
    }

    public function getMpCost() {
        return $this->mpCost;
    }

    public function getIcon() {
        return $this->icon;
    }

    public function setIcon($icon) {
        $this->icon = $icon;
    }

    public function setApCost($apCost) {
        $this->apCost = $apCost;
    }

    public function setMpCost($mpCost) {
        $this->mpCost = $mpCost;
    }

    public function setOptions($options) {
        $this->options = $options;
    }

    public function prepareOptions(EntityManager $manager, $uuid): array {
        $result = [];
        $optionArray = json_decode($this->options, true);
        if (!is_array($optionArray)) {
            return $result;
        }
        foreach ($optionArray as $params) {
            $option = Option::FACTORY($manager, $uuid, $params);
            if ($option) {
                $result[] = $option;
            }
        }
        return $result;
    }

}
