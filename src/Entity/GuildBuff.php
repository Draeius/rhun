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

/**
 * Description of GuildBuff
 *
 * @author Draeius
 * @Entity
 * @Table(name="guild_buffs")
 */
class GuildBuff {

    /**
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * 
     * @var GuildBuffTemplate
     * @ManyToOne(targetEntity="App\Entity\GuildBuffTemplate")
     * @JoinColumn(name="template_id", referencedColumnName="id")
     */
    protected $guildBuffTemplate;

    /**
     * 
     * @var Guild 
     * @ManyToOne(targetEntity="App\Entity\Guild", inversedBy="buffs")
     * @JoinColumn(name="guild_id", referencedColumnName="id")
     */
    protected $guild;

    public function getId() {
        return $this->id;
    }

    public function getGuildBuffTemplate(): GuildBuffTemplate {
        return $this->guildBuffTemplate;
    }

    public function getGuild(): Guild {
        return $this->guild;
    }

    public function setGuildBuffTemplate(GuildBuffTemplate $guildBuffTemplate) {
        $this->guildBuffTemplate = $guildBuffTemplate;
    }

    public function setGuild(Guild $guild) {
        $this->guild = $guild;
    }

}
