<?php

namespace App\Controller;

use App\Controller\BasicController;
use App\Entity\Character;
use App\Entity\DiaryEntry;
use App\Repository\CharacterRepository;
use App\Repository\UserRepository;
use App\Service\ParamGenerator\DiaryParamGenerator;
use App\Service\SkinService;
use App\Util\Session\RhunSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of DiaryController
 *
 * @author Draeius
 */
class DiaryController extends BasicController {

    /**
     *
     * @var DiaryParamGenerator
     */
    private $paramGenerator;

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    function __construct(SkinService $skinService, DiaryParamGenerator $paramGenerator, EntityManagerInterface $eManager) {
        parent::__construct($skinService);
        $this->paramGenerator = $paramGenerator;
        $this->eManager = $eManager;
    }

    /**
     * @Route("diary/editor", name="diary_editor")
     */
    public function showEditor(Request $request, CharacterRepository $charRepo, UserRepository $userRepo) {
        $session = new RhunSession();
        $account = $userRepo->find($session->getAccountID());

        return $this->render($this->getSkinFile(), $this->paramGenerator->getDiaryEditorParams($charRepo, $account, $request->get('selectedChar')));
    }

    /**
     * @Route("diary/show/{charId}/{diaryId}/{uuid}", name="diary_show", defaults={"uuid"="1"})
     */
    public function showDiary($charId, $diaryId, $uuid, CharacterRepository $charRepo, UserRepository $userRepo) {
        $diaryEntry = $this->eManager->find('App:DiaryEntry', $diaryId);

        /* @var $character Character */
        $character = $charRepo->find($charId);

        $settings = $this->eManager->find('App:ServerSettings', 1);

        return $this->render($this->getSkinFile(), $this->paramGenerator->getCharacterDiaryParams($character, $diaryEntry, $settings, $charRepo));
    }

    /**
     * @Route("diary/get/entries", name="diary_entries")
     */
    public function getDiaryEntries(Request $request, CharacterRepository $charRepo) {
        /* @var $character Character */
        $character = $charRepo->find($request->get('charId'));

        $diaryEntries = $this->eManager->getRepository('App:DiaryEntry')->findBy(['owner' => $character]);

        $result = [];
        foreach ($diaryEntries as $entry) {
            $result[] = ['title' => $entry->getTitle(), 'id' => $entry->getId()];
        }
        return new JsonResponse($result);
    }

    /**
     * @Route("diary/get/data", name="diary_data")
     */
    public function getDiaryData(Request $request) {
        $diaryEntry = $this->eManager->find('App:DiaryEntry', $request->get('id'));

        $result = array(
            'title' => $diaryEntry->getTitle(),
            'text' => $diaryEntry->getText(),
            'script' => $diaryEntry->getScript()
        );
        return new JsonResponse($result);
    }

    /**
     * @Route("diary/add", name="diary_add")
     */
    public function addDiaryEntry(Request $request, CharacterRepository $charRepo) {
        $character = $charRepo->find($request->get('character'));

        $entry = new DiaryEntry();
        $entry->setTitle($request->get('title'));
        $entry->setOwner($character);

        $this->eManager->persist($entry);
        $this->eManager->flush();

        return new JsonResponse(['msg' => 'Tagebucheintrag erfolgreich hinzugefügt']);
    }

    /**
     * @Route("diary/edit", name="diary_edit")
     */
    public function editDiaryEntry(Request $request) {
        /* @var $diaryEntry DiaryEntry */
        $diaryEntry = $this->eManager->find('App:DiaryEntry', $request->get('id'));

        if (!$diaryEntry) {
            return new JsonResponse(['error' => 'Tagebucheintrag nicht gefunden.']);
        }

        $diaryEntry->setText($request->get('text'));
        $diaryEntry->setScript($request->get('script'));
        if ($request->get('title') != '') {
            $diaryEntry->setTitle($request->get('title'));
        }

        $this->eManager->persist($diaryEntry);
        $this->eManager->flush();

        return new JsonResponse(['msg' => 'Tagebucheintrag erfolgreich geändert.']);
    }

    /**
     * @Route("diary/del", name="diary_del")
     */
    public function removeDiaryEntry(Request $request, UserRepository $userRepo) {
        /* @var $diaryEntry DiaryEntry */
        $diaryEntry = $this->eManager->find('App:DiaryEntry', $request->get('id'));

        $session = new RhunSession();
        $account = $userRepo->find($session->getAccountID());

        if ($account->getId() != $diaryEntry->getOwner()->getAccount()->getId()) {
            return new JsonResponse(['error' => 'Dieser Tagebucheintrag gehört dir nicht.']);
        }

        $this->eManager->remove($diaryEntry);
        $this->eManager->flush();

        return new JsonResponse(['msg' => 'Tagebucheintrag erfolgreich gelöscht.']);
    }

}
