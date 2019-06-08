<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use AppBundle\Entity\Character;
use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
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
class Buff {

    /**
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * 
     * @var Character
     * @ManyToOne(targetEntity="AppBundle\Entity\Character", inversedBy="buffs")
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

    public function __construct() {
        
    }

    public function getId() {
        return $this->id;
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
