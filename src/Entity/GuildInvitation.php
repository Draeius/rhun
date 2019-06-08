<?php

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
 * Description of GuildInvitation
 *
 * @author Draeius
 * @Entity
 * @Table(name="guild_invitations")
 */
class GuildInvitation {

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
     * @ManyToOne(targetEntity="AppBundle\Entity\Character")
     * @JoinColumn(name="character_id", referencedColumnName="id")
     */
    protected $character;

    /**
     * 
     * @var Guild
     * @ManyToOne(targetEntity="Guild")
     * @JoinColumn(name="guild_id", referencedColumnName="id")
     */
    protected $guild;

    /**
     *
     * @var DateTime
     * @Column(type="datetime")
     */
    protected $created;

    public function getId() {
        return $this->id;
    }

    public function getCharacter(): Character {
        return $this->character;
    }

    public function getGuild(): Guild {
        return $this->guild;
    }

    public function getCreated(): DateTime {
        return $this->created;
    }

    public function setCharacter(Character $character) {
        $this->character = $character;
    }

    public function setGuild(Guild $guild) {
        $this->guild = $guild;
    }

    public function setCreated(DateTime $created) {
        $this->created = $created;
    }

}
