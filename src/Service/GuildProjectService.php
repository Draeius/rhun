<?php

namespace App\Service;

use App\Entity\Guild;
use App\Entity\GuildProject;

/**
 * Description of GuildProjectService
 *
 * @author Draeius
 */
class GuildProjectService {

    /**
     *
     * @var configService
     */
    private $config;

    function __construct(ConfigService $config) {
        $this->config = $config;
    }

    public function factory(int $type, Guild $guild): GuildProject {
        switch ($type) {
            case GuildProject::BUILD_ROOM_PROJECT:
                return $this->buildRoomFactory($guild);
            case GuildProject::BUILD_ROOM_TRAINING:
                return $this->buildTrainingFactory($guild);
        }
    }

    public function buildRoomFactory(Guild $guild): GuildProject {
        $project = new GuildProject();
        $project->setGuild($guild);

        $guildSize = sizeof($guild->getGuildHall()->getLocations()) - 2;
        foreach ($guild->getProjects() as $pending) {
            if ($pending->getType() == GuildProject::BUILD_ROOM_PROJECT) {
                $guildSize++;
            }
        }

        $config = $this->config->getGuildConfig();

        $project->setPrice($config->getAddRoomBasePrice()->add($config->getAddRoomPriceAddition()->multiply($guildSize)));
        $project->setType(GuildProject::BUILD_ROOM_PROJECT);

        return $project;
    }

    public function buildTrainingFactory(Guild $guild): GuildProject {
        $project = new GuildProject();
        $project->setGuild($guild);

        $guildSize = sizeof($guild->getGuildHall()->getLocations()) - 2;
        foreach ($guild->getProjects() as $pending) {
            if ($pending->getType() == GuildProject::BUILD_ROOM_PROJECT) {
                $guildSize++;
            }
        }

        $config = $this->config->getGuildConfig();

        $project->setPrice($config->getAddTrainingPrice());
        $project->setType(GuildProject::BUILD_ROOM_TRAINING);

        return $project;
    }

}
