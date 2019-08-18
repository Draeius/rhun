<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service\ParamGenerator\Location;

use App\Entity\LocationBase;
use App\Service\DateTimeService;
use App\Service\ParamGenerator\ParamGenerator;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of LocationParamGeneratorBase
 *
 * @author Matthias
 */
abstract class LocationParamGeneratorBase extends ParamGenerator {

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    public function __construct(DateTimeService $dtService, EntityManagerInterface $eManager) {
        parent::__construct($dtService);
        $this->eManager = $eManager;
    }

    protected function getEntityManager(): EntityManagerInterface {
        return $this->eManager;
    }

    public abstract function getParams(LocationBase $location): array;
}
