<?php

namespace App\Service\NavbarFactory\Location;

use App\Entity\Character;
use App\Service\ConfigService;
use App\Service\DateTimeService;
use App\Service\NavbarService;
use Doctrine\ORM\EntityManagerInterface;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NavbarModifierBase
 *
 * @author Draeius
 */
abstract class NavbarModifierBase {

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

    public abstract function modifyNavbar(NavbarService $service, Location $location): array;
}
