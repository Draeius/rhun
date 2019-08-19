<?php

namespace App\Service\ParamGenerator\Location;

use App\Entity\Character;
use App\Entity\LocationBase;
use App\Service\ConfigService;
use App\Service\DateTimeService;
use App\Service\ParamGenerator\ParamGenerator;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of LocationParamGeneratorBase
 *
 * @author Draeius
 */
abstract class LocationParamGeneratorBase extends ParamGenerator {

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    /**
     *
     * @var Character
     */
    private $character;

    /**
     *
     * @var ConfigService
     */
    private $config;

    public function __construct(Character $character, DateTimeService $dtService, EntityManagerInterface $eManager, ConfigService $config) {
        parent::__construct($dtService);
        $this->eManager = $eManager;
        $this->character = $character;
        $this->config = $config;
    }

    protected function getEntityManager(): EntityManagerInterface {
        return $this->eManager;
    }

    protected function getCharacter(): Character {
        return $this->character;
    }

    protected function getConfig(): ConfigService {
        return $this->config;
    }

    public abstract function getParams(LocationBase $location): array;
}
