<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use AppBundle\Util\Price;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

/**
 *
 * @author Draeius
 * @Entity
 * @Table(name="guild_projects")
 */
class GuildProject {

    const BUILD_ROOM_PROJECT = 0;

    /**
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * 
     * @var Guild
     * @ManyToOne(targetEntity="Guild", inversedBy="projects")
     * @JoinColumn(name="guild_id", referencedColumnName="id")
     */
    protected $guild;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $type;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $priceGold;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $pricePlatin;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $priceGems;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $progressGold = 0;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $progressPlatin = 0;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $progressGems = 0;

    /**
     * 
     * @var GuildProjectItem[] 
     * @OneToMany(targetEntity="GuildProjectItem", mappedBy="project", cascade={"remove", "persist"}, fetch="EXTRA_LAZY")
     */
    protected $items;

    public function __construct() {
        $this->items = new ArrayCollection();
    }

    public function isReady(): bool {
        $ready = true;
        $ready &= $this->priceGems <= $this->progressGems;
        $ready &= $this->pricePlatin <= $this->progressPlatin;
        $ready &= $this->priceGold <= $this->progressGold;

        foreach ($this->items as $item) {
            $ready &= $item->isReady();
        }
        return $ready;
    }

    public function addGold(int $amount) {
        $this->progressGold += $amount;
        if ($this->progressGold >= $this->priceGold) {
            $this->progressGold = $this->priceGold;
        }
    }

    public function addPlatin(int $amount) {
        $this->progressPlatin += $amount;
        if ($this->progressPlatin >= $this->pricePlatin) {
            $this->progressPlatin = $this->pricePlatin;
        }
    }

    public function addGems(int $amount) {
        $this->progressGems += $amount;
        if ($this->progressGems >= $this->priceGems) {
            $this->progressGems = $this->priceGems;
        }
    }

    public function addPrice(Price &$price) {
        if ($price->getGold() > $this->priceGold - $this->progressGold) {
            $price->setGold($this->priceGold - $this->progressGold);
        }
        if ($price->getPlatin() > $this->pricePlatin - $this->progressPlatin) {
            $price->setPlatin($this->pricePlatin - $this->progressPlatin);
        }if ($price->getGems() > $this->priceGems - $this->progressGems) {
            $price->setGems($this->priceGems - $this->progressGems);
        }

        $this->addGold($price->getGold());
        $this->addPlatin($price->getPlatin());
        $this->addGems($price->getGems());
    }

    public function getId() {
        return $this->id;
    }

    public function getGuild(): Guild {
        return $this->guild;
    }

    public function getType() {
        return $this->type;
    }

    public function getPriceGold() {
        return $this->priceGold;
    }

    public function getPricePlatin() {
        return $this->pricePlatin;
    }

    public function getPriceGems() {
        return $this->priceGems;
    }

    public function getProgressGold() {
        return $this->progressGold;
    }

    public function getProgressPlatin() {
        return $this->progressPlatin;
    }

    public function getProgressGems() {
        return $this->progressGems;
    }

    public function getItems() {
        return $this->items;
    }

    public function setItems($items) {
        $this->items = $items;
    }

    public function setGuild(Guild $guild) {
        $this->guild = $guild;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setPriceGold($priceGold) {
        $this->priceGold = $priceGold;
    }

    public function setPricePlatin($pricePlatin) {
        $this->pricePlatin = $pricePlatin;
    }

    public function setPriceGems($priceGems) {
        $this->priceGems = $priceGems;
    }

    public function setProgressGold($progressGold) {
        $this->progressGold = $progressGold;
    }

    public function setProgressPlatin($progressPlatin) {
        $this->progressPlatin = $progressPlatin;
    }

    public function setProgressGems($progressGems) {
        $this->progressGems = $progressGems;
    }

}
