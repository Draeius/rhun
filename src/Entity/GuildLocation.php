<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of GuildLocation
 *
 * @author Draeius
 * @Entity
 * @Table(name="guild_locations")
 */
class GuildLocation extends LocationBasedEntity {

    /**
     *
     * @var House
     * @ManyToOne(targetEntity="Guild", inversedBy="rooms")
     * @JoinColumn(name="guild_id", referencedColumnName="id")
     */
    protected $guild;

}
