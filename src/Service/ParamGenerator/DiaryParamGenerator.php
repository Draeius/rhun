<?php

namespace App\Service\ParamGenerator;

use App\Entity\Character;
use App\Entity\DiaryEntry;
use App\Entity\ServerSettings;
use App\Repository\CharacterRepository;
use App\Service\DateTimeService;
use App\Service\NavbarFactory\DiaryNavbarGenerator;
use Symfony\Component\Security\Core\User\User;

/**
 * Description of DiaryParamGenerator
 *
 * @author Draeius
 */
class DiaryParamGenerator extends ParamGenerator {

    /**
     *
     * @var DiaryNavbarGenerator
     */
    private $navFactory;

    public function __construct(DateTimeService $dtService, DiaryNavbarGenerator $navFactory) {
        parent::__construct($dtService);
        $this->navFactory = $navFactory;
    }

    public function getDiaryEditorParams(CharacterRepository $charRepo, User $account, $selectedChar) {
        return array_merge($this->getBaseParams('Tagebuch', $this->navFactory->getEditorNavbar()), [
            'page' => 'biography/bioeditor',
            'characters' => $charRepo->findByAccount($account),
            'selectedChar' => $selectedChar
        ]);
    }

    public function getCharacterDiaryParams(Character $character, DiaryEntry $entry, ServerSettings $settings, CharacterRepository $charRepo) {
        return array_merge($this->getBaseParams('Biographie', $this->navFactory->getDiaryNavbar($character, $charRepo, $settings)), [
            'page' => 'biography/biography',
            'diary' => $entry
        ]);
    }

}
