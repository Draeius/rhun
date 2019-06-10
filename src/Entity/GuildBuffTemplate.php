<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of GuildBuffTemplate
 *
 * @author Matthias
 * @Entity
 * @Table(name="templates_guild_buff")
 */
class GuildBuffTemplate extends RhunEntity {

    /**
     * 
     * @var BuffTemplate 
     * @ManyToOne(targetEntity="BuffTemplate")
     * @JoinColumn(name="template_id", referencedColumnName="id")
     */
    protected $buffTemplate;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $guildLevel;

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
