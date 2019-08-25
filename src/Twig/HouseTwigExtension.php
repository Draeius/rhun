<?php

namespace App\Twig;

use App\Service\ConfigService;
use App\Service\HouseService;
use Twig_Extension;
use Twig_SimpleFilter;

/**
 * Description of HouseTwigExtension
 *
 * @author Draeius
 */
class HouseTwigExtension extends Twig_Extension {

    /**
     *
     * @var ConfigService
     */
    private $config;

    function __construct(ConfigService $config) {
        $this->config = $config;
    }

    public function getFilters() {
        return array(
            new Twig_SimpleFilter('houseLevel', array($this, 'getHouseLevel')),
        );
    }

    public function getHouseLevel($numberRooms) {
        return HouseService::getHouseLevel($numberRooms, $this->config->getHouseConfig());
    }

    public function getName() {
        return 'house_twig_extension';
    }

}
