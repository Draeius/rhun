<?php

namespace App\Entity;

use App\Entity\Item;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of GuildProjectItem
 *
 * @author Draeius
 * @Entity
 * @Table(name="guild_project_items")
 */
class GuildProjectItem extends RhunEntity {

    /**
     * 
     * @var Item 
     * @ManyToOne(targetEntity="GuildProject", inversedBy="items")
     * @JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project;

    /**
     * 
     * @var Item 
     * @ManyToOne(targetEntity="App\Entity\Items\Item")
     * @JoinColumn(name="item_id", referencedColumnName="id")
     */
    protected $item;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $neededAmount;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $progress = 0;

    public function isReady(): bool {
        return $this->neededAmount <= $this->progress;
    }

    public function addProgress(int $amount) {
        $this->progress += $amount;
        if ($this->progress > $this->neededAmount) {
            $this->progress = $this->neededAmount;
        }
    }

    public function getProject(): Item {
        return $this->project;
    }

    public function getItem(): Item {
        return $this->item;
    }

    public function getNeededAmount() {
        return $this->neededAmount;
    }

    public function getProgress() {
        return $this->progress;
    }

    public function setProject(Item $project) {
        $this->project = $project;
    }

    public function setItem(Item $item) {
        $this->item = $item;
    }

    public function setNeededAmount($neededAmount) {
        $this->neededAmount = $neededAmount;
    }

    public function setProgress($progress) {
        $this->progress = $progress;
    }

}
