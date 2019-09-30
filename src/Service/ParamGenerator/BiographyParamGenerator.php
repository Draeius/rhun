<?php

namespace App\Service\ParamGenerator;

use App\Entity\Biography;
use App\Entity\Character;
use App\Entity\House;
use App\Entity\ServerSettings;
use App\Repository\CharacterRepository;
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
            'page' => 'biography/houseDescription',
            'house' => $house
        ]);
    }

    public function getCharacterBioParams(Character $character, ServerSettings $settings, CharacterRepository $charRepo) {
        return array_merge($this->getBaseParams('Biographie', $this->navFactory->getBiographyNavbar($character, $charRepo, $settings)), [
            'page' => 'biography/biography',
            'character' => $character,
            'settings' => $settings,
            'biography' => $this->getBiography($character, $settings)
        ]);
    }

    public function getBioEditorParams(CharacterRepository $charRepo, User $account, $selectedChar) {
        return array_merge($this->getBaseParams('Biographie', $this->navFactory->getEditorNavbar()), [
            'page' => 'biography/bioeditor',
            'characters' => $charRepo->findByAccount($account),
            'selectedChar' => $selectedChar
        ]);
    }

    private function getBiography(Character $character, ServerSettings $settings): ?Biography {
        foreach ($character->getBiography() as $bio) {
            if ($settings->getUseMaskedBios() && $bio->getMaskedBall()) {
                return $bio;
            } elseif ($bio->getSelected()) {
                return $bio;
            }
        }
        return null;
    }

}
