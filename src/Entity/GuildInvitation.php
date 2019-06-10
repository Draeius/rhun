<?php

namespace App\Entity;

use App\Entity\Traits\EntityCreatedTrait;
use App\Entity\Traits\EntityOwnerTrait;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of GuildInvitation
 *
 * @author Draeius
 * @Entity
 * @Table(name="guild_invitations")
 * @HasLifecycleCallbacks
 */
class GuildInvitation extends RhunEntity {

    use EntityCreatedTrait;
    use EntityOwnerTrait;

    /**
     * 
     * @var Guild
     * @ManyToOne(targetEntity="Guild")
     * @JoinColumn(name="guild_id", referencedColumnName="id")
     */
    protected $guild;

    public function getGuild(): Guild {
        return $this->guild;
    }

    public function setGuild(Guild $guild) {
        $this->guild = $guild;
    }

}
