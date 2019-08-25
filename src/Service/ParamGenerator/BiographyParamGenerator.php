<?php

namespace App\Service\ParamGenerator;

use App\Entity\Character;
use App\Service\DateTimeService;
use App\Service\NavbarFactory\BiographyNavbarGenerator;
use App\Util\Session\RhunSession;

/**
 * Description of BiographyParamGenerator
 *
 * @author Draeius
 */
class BiographyParamGenerator extends ParamGenerator {

    /**
     *
     * @var BiographyNavbarGenerator
     */
    private $navFactory;

    public function __construct(DateTimeService $dtService, BiographyNavbarGenerator $navFactory) {
        parent::__construct($dtService);
        $this->navFactory = $navFactory;
    }

    public function getHouseBioParams(House $house, Character $character) {
        $session = new RhunSession();
        return array_merge($this->getBaseParams('Hausbeschreibung', $this->navFactory->getStandardNavbar($session->getTabIdentifier(), $character)), [
            'page' => 'default/houseDescription',
            'house' => $house
        ]);
    }

}
