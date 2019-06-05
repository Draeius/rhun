<?php

namespace App\Entity\Partial;

use App\Service\DateTimeService;
use DateTime;

/**
 * Description of CharacterListPartial
 *
 * @author Matthias
 */
class CharacterListPartial extends CharacterNamePartial {

    /**
     *
     * @var bool
     */
    protected $gender;

    /**
     *
     * @var GuildCharListPartial
     */
    protected $guild;

    /**
     *
     * @var int
     */
    protected $level;

    /**
     *
     * @var LocationNamePartial
     */
    protected $location;

    /**
     *
     * @var RacePartial 
     */
    protected $race;

    /**
     *
     * @var bool
     */
    protected $dead;

    /**
     *
     * @var DateTime
     */
    protected $lastActive;

    /**
     *
     * @var bool
     */
    protected $online;

    function getGender() {
        return $this->gender;
    }

    function getGuild(): ?GuildCharListPartial {
        return $this->guild;
    }

    function getLevel() {
        return $this->level;
    }

    function getRace(): RacePartial {
        return $this->race;
    }

    function getLocation(): LocationNamePartial {
        return $this->location;
    }

    function getDead() {
        return $this->dead;
    }

    function getLastActive(): DateTime {
        return $this->lastActive;
    }

    function getOnline() {
        return $this->online;
    }

    public static function FROM_DATA(array $data): CharacterNamePartial {
        $partial = new self();
        $partial->fromData($data);
        return $partial;
    }

    protected function fromData(array $data) {
        parent::fromData($data);
        $this->level = $data['level'];
        $this->dead = $data['dead'];
        $this->lastActive = DateTimeService::getDateTime($data['lastActive']);
        $this->online = $data['online'];
        $this->gender = $data['gender'];

        if ($data['guildName']) {
            $this->guild = GuildCharListPartial::fromData(['name' => $data['guildName'], 'tag' => $data['guildTag']]);
        }
        $this->location = LocationNamePartial::fromData(['title' => $data['locationTitle']]);
        $this->race = RacePartial::fromData(['name' => $data['raceName'], 'city' => '', 'description' => '']);
    }

}
