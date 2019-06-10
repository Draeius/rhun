<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use App\Entity\Character;
use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of Buff
 *
 * @author Draeius
 * @Entity
 * @Table(name="buffs")
 */
class Buff extends RhunEntity {

    /**
     * 
     * @var Character
     * @ManyToOne(targetEntity="Character", inversedBy="buffs")
     * @JoinColumn(name="owner_id", referencedColumnName="id")
     */
    protected $owner;

    /**
     * 
     * @var BuffTemplate
     * @ManyToOne(targetEntity="BuffTemplate")
     * @JoinColumn(name="template_id", referencedColumnName="id")
     */
    protected $template;

    /**
     * 
     * @var DateTime
     * @Column(type="datetime", nullable=true)
     */
    protected $endDate;

    /**
     *
     * @var integer
     * @Column(type="integer", nullable=true)
     */
    protected $maxRounds;

    public function isHigherTier(Buff $other): bool {
        return $this->template->getLevel() > $other->getTemplate()->getLevel();
    }

    public function isSameTier(Buff $other): bool {
        return $this->template->getLevel() == $other->getTemplate()->getLevel();
    }

    public function isLowerTier(Buff $other): bool {
        return $this->template->getLevel() < $other->getTemplate()->getLevel();
    }

    public function isPercentageHPBuff(): bool {
        return $this->template->getHPBuff() > -1 && $this->template->getHPBuff() < 1;
    }

    public function isPercentageStaminaBuff(): bool {
        return $this->template->getStaminaBuff() > -1 && $this->template->getStaminaBuff() < 1;
    }

    public function isPercentageMPBuff(): bool {
        return $this->template->getMPBuff() > -1 && $this->template->getMPBuff() < 1;
    }

    public function getOwner() {
        return $this->owner;
    }

    public function getTemplate() {
        return $this->template;
    }

    public function getEndDate() {
        return $this->endDate;
    }

    public function getMaxRounds() {
        return $this->maxRounds;
    }

    public function setMaxRounds($maxRounds) {
        $this->maxRounds = $maxRounds;
    }

    public function setOwner($owner) {
        $this->owner = $owner;
    }

    public function setTemplate($template) {
        $this->template = $template;
    }

    public function setEndDate($endDate) {
        $this->endDate = $endDate;
    }

}
