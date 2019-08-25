<?php

namespace App\Service\ParamGenerator;

use App\Entity\Character;
use App\Entity\Location;
use App\Entity\LocationBase;
use App\Repository\NavigationRepository;
use App\Service\ConfigService;
use App\Service\DateTimeService;
use App\Service\NavbarFactory\WorldNavbarFactory;
use App\Service\ParamGenerator\Location\LocationParamGeneratorBase;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * Description of WorldParamGenerator
 *
 * @author Draeius
 */
class WorldParamGenerator extends ParamGenerator {

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    /**
     *
     * @var WorldNavbarFactory
     */
    private $navFactory;

    /**
     *
     * @var ConfigService
     */
    private $config;

    function __construct(DateTimeService $dtService, EntityManagerInterface $eManager, WorldNavbarFactory $navFactory, ConfigService $config) {
        parent::__construct($dtService);
        $this->eManager = $eManager;
        $this->navFactory = $navFactory;
        $this->config = $config;
    }

    public function getWorldParams(LocationBase $location, Character $character, NavigationRepository $navRepo): array {
        $params = $this->getLocationParams($location, $character, $navRepo);
        return $params;
    }

    public function getLocationParams(Location $location, Character $character, NavigationRepository $navRepo) {
        $addIns = $location->getDataArray();

        $params = [];

        foreach ($addIns as $key => $active) {
            if ($active && $generator = $this->createParamGenerator($key, $character)) {
                $params = array_merge($params, $generator->getParams($location));
            }
        }

        return array_merge($this->getBaseParams('', $this->navFactory->buildNavbar($location, $character, $navRepo)), $params, [
            'page' => 'world/worldLocation',
            'addIns' => $addIns,
            'charsPresent' => true,
            'showLinks' => true,
            'showMails' => true,
            'locationtitle' => $location->getName(),
            'description' => $location->currentDescription(),
            'character' => $character
        ]);
    }

    private function getParamGeneratorClass(string $index) {
        return 'App\\Service\\ParamGenerator\\Location\\' . ucfirst($index) . 'Generator';
    }

    private function createParamGenerator(string $index, Character $character) {
        $class = $this->getParamGeneratorClass($index);
        if (!class_exists($class)) {
            return false;
        }
        $generator = new $class($character, $this->getDtService(), $this->eManager, $this->config);
        if (!$generator instanceof LocationParamGeneratorBase) {
            throw new Exception($class . ' is no valid ParamGenerator. Must be subclass of LocationParamGenerator');
        }
        return $generator;
    }

}
