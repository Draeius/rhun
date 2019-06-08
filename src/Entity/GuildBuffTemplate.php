<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use FightingBundle\Entity\BuffTemplate;

/**
 * Description of GuildBuffTemplate
 *
 * @author Matthias
 * @Entity
 * @Table(name="guild_buff_templates")
 */
class GuildBuffTemplate {

    /**
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * 
     * @var BuffTemplate 
     * @ManyToOne(targetEntity="FightingBundle\Entity\BuffTemplate")
     * @JoinColumn(name="template_id", referencedColumnName="id")
     */
    protected $buffTemplate;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $guildLevel;

    public function getId() {
        return $this->id;
    }

    public function getBuffTemplate(): BuffTemplate {
        return $this->buffTemplate;
    }

    public function getGuildLevel() {
        return $this->guildLevel;
    }

    public function setBuffTemplate(BuffTemplate $buffTemplate) {
        $this->buffTemplate = $buffTemplate;
    }

    public function setGuildLevel($guildLevel) {
        $this->guildLevel = $guildLevel;
    }

}
