<?php

namespace App\Entity;

use App\Entity\Item;
use App\Entity\Items\Item as Item2;
use App\Entity\Traits\EntityAttributesTrait;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of Quest
 * 
 * @author Draeius
 * @Entity
 * @Table(name="quests")
 */
class Quest extends LocationBasedEntity {

    use EntityAttributesTrait;

    /**
     *
     * @var string
     * @Column(type="text", length=64)
     */
    protected $navLabel;

    /**
     *
     * @var string
     * @Column(type="text")
     */
    protected $textIntroduction;

    /**
     *
     * @var string
     * @Column(type="text")
     */
    protected $textFinished;

    /**
     *
     * @var string
     * @Column(type="text")
     */
    protected $textFail;

    /**
     *
     * @var Item
     * @ManyToOne(targetEntity="Item2")
     * @JoinColumn(name="needed_item_id", referencedColumnName="id")
     */
    protected $neededItem;

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
    protected $rewardHP;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $rewardActionPoints;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $rewardGold;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $rewardPlatin;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $rewardGems;

    /**
     *
     * @var Item;
     * @ManyToOne(targetEntity="Item2")
     * @JoinColumn(name="reward_item_id", referencedColumnName="id", nullable=true)
     */
    protected $rewardItem;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $rewardAmount;

    /**
     *
     * @var boolean
     * @Column(type="boolean")
     */
    protected $available;

    public function getTextIntroduction() {
        return $this->textIntroduction;
    }

    public function getTextFinished() {
        return $this->textFinished;
    }

    public function getTextFail() {
        return $this->textFail;
    }

    public function getNeededItem(): Item {
        return $this->neededItem;
    }

    public function getRewardHP() {
        return $this->rewardHP;
    }

    public function getNeededAmount() {
        return $this->neededAmount;
    }

    public function getRewardItem() {
        return $this->rewardItem;
    }

    public function getRewardAmount() {
        return $this->rewardAmount;
    }

    public function getNavLabel() {
        return $this->navLabel;
    }

    public function getRewardActionPoints() {
        return $this->rewardActionPoints;
    }

    public function getRewardGold() {
        return $this->rewardGold;
    }

    public function getRewardPlatin() {
        return $this->rewardPlatin;
    }

    public function getRewardGems() {
        return $this->rewardGems;
    }

    public function getAvailable() {
        return $this->available;
    }

    public function setRewardActionPoints($rewardActionPoints) {
        $this->rewardActionPoints = $rewardActionPoints;
    }

    public function setRewardGold($rewardGold) {
        $this->rewardGold = $rewardGold;
    }

    public function setRewardPlatin($rewardPlatin) {
        $this->rewardPlatin = $rewardPlatin;
    }

    public function setRewardGems($rewardGems) {
        $this->rewardGems = $rewardGems;
    }

    public function setAvailable($available) {
        $this->available = $available;
    }

    public function setNavLabel($navLabel) {
        $this->navLabel = $navLabel;
    }

    public function setNeededAmount($neededAmount) {
        $this->neededAmount = $neededAmount;
    }

    public function setRewardItem($rewardItem) {
        $this->rewardItem = $rewardItem;
    }

    public function setRewardAmount($rewardAmount) {
        $this->rewardAmount = $rewardAmount;
    }

    public function setNeededItem($neededItem) {
        $this->neededItem = $neededItem;
    }

    public function setRewardHP($rewardHP) {
        $this->rewardHP = $rewardHP;
    }

    public function setTextFail($textFail) {
        $this->textFail = $textFail;
    }

    public function setTextIntroduction($textIntroduction) {
        $this->textIntroduction = $textIntroduction;
    }

    public function setTextFinished($textFinished) {
        $this->textFinished = $textFinished;
    }

}
