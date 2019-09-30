<?php

namespace App\Controller;

use App\Entity\Biography;
use App\Entity\Character;
use App\Repository\CharacterRepository;
use App\Repository\UserRepository;
use App\Service\ParamGenerator\BiographyParamGenerator;
use App\Service\SkinService;
use App\Util\Session\RhunSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @App\Annotation\Security(needAccount=true)
 */
class BiographyController extends BasicController {

    /**
     *
     * @var BiographyParamGenerator
     */
    private $paramGenerator;

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    function __construct(SkinService $skinService, BiographyParamGenerator $paramGenerator, EntityManagerInterface $eManager) {
        parent::__construct($skinService);
        $this->paramGenerator = $paramGenerator;
        $this->eManager = $eManager;
    }

    /**
     * @Route("bio/editor", name="bioEditor")
     */
    public function showEditor(Request $request, CharacterRepository $charRepo, UserRepository $userRepo) {
        $session = new RhunSession();
        $account = $userRepo->find($session->getAccountID());

        return $this->render($this->getSkinFile(), $this->paramGenerator->getBioEditorParams($charRepo, $account, $request->get('selectedChar')));
    }

    /**
     * @Route("/bio/{charId}/{uuid}", name="bio", defaults={"uuid"="1"})
     */
    public function showBiography($charId, $uuid, CharacterRepository $charRepo) {
        /* @var $character Character */
        $character = $charRepo->find($charId);

        $settings = $this->eManager->find('App:ServerSettings', 1);

        return $this->render($this->getSkinFile(), $this->paramGenerator->getCharacterBioParams($character, $settings, $charRepo));
    }

    /**
     * @Route("/bio_editor/get/data", name="bio_data")
     */
    public function getBiographyData(Request $request) {
        $bio = $this->eManager->find('App:Biography', $request->get('id'));

        $result = array(
            'script' => $bio->getScript(),
            'text' => $bio->getText(),
            'selected' => $bio->getSelected(),
            'ballbio' => $bio->getMaskedBall(),
            'alternateName' => $bio->getAlternateCharName(),
            'standardAction' => $bio->getStandardColorAction(),
            'standardSpeech' => $bio->getStandardColorSpeech(),
            'hairstyle' => $bio->getHaircut(),
            'haircolor' => $bio->getHaircolor(),
            'height' => $bio->getHeight(),
            'ethnicity' => $bio->getEthnicity(),
            'age' => $bio->getAge(),
            'disposition' => $bio->getDisposition(),
            'eyecolor' => $bio->getEyecolor(),
            'avatar' => $bio->getAvatar()
        );
        return new JsonResponse($result);
    }

    /**
     * @Route("/bio_editor/add", name="bio_add")
     */
    public function addBiography(Request $request, CharacterRepository $charRepo) {
        $character = $charRepo->find($request->get('character'));

        $bio = new Biography();
        $bio->setName($request->get('name'));
        $bio->setOwner($character);

        $this->eManager->persist($bio);
        $this->eManager->flush();

        return new JsonResponse(['msg' => 'Bio erfolgreich hinzugefügt']);
    }

    /**
     * @Route("/bio_editor/edit", name="bio_edit")
     */
    public function editBiography(Request $request) {
        /* @var $bio Biography */
        $bio = $this->eManager->find('App:Biography', $request->get('bio'));

        if (!$bio) {
            return new JsonResponse(['error' => 'Bio nicht gefunden.']);
        }

        if ($bio->getScript() != $request->get('script')) {
            $bio->setScriptChanged = true;
        }

        $bio->setText($request->get('text'));
        $bio->setScript($request->get('script'));
        $bio->setSelected($request->get('selected') != null);
        $bio->setAlternateCharName($request->get('charName'));
        $bio->setMaskedBall($request->get('masked') != NULL);
        $bio->setStandardColorSpeech($request->get('standardSpeech'));
        $bio->setStandardColorAction($request->get('standardAction'));
        $bio->setHaircolor($request->get('haircolor'));
        $bio->setHaircut($request->get('haircut'));
        $bio->setHeight($request->get('height'));
        $bio->setEthnicity($request->get('ethnicity'));
        $bio->setAge($request->get('age'));
        $bio->setDisposition($request->get('disposition'));
        $bio->setEyecolor($request->get('eyecolor'));
        $bio->setAvatar($request->get('avatar'));

        $this->eManager->persist($bio);
        if ($bio->getSelected()) {
            foreach ($bio->getOwner()->getBiography() as $bioCheck) {
                $bioCheck->setSelected($bioCheck->getId() == $bio->getId());
                $this->eManager->persist($bioCheck);
            }
        }
        if ($bio->getMaskedBall()) {
            foreach ($bio->getOwner()->getBiography() as $bioCheck) {
                $bioCheck->setMaskedBall($bioCheck->getId() == $bio->getId());
                $this->eManager->persist($bioCheck);
            }
        }
        $this->eManager->flush();

        return new JsonResponse(['msg' => 'Biographie erfolgreich geändert.']);
    }

    /**
     * @Route("/bio_editor/del", name="bio_del")
     */
    public function removeBiography(Request $request, UserRepository $userRepo) {
        $bio = $this->eManager->find('App:Biography', $request->get('id'));
        if (!$bio) {
            return new JsonResponse(['error' => 'Diese Biography gehört dir nicht.']);
        }

        $session = new RhunSession();
        $account = $userRepo->find($session->getAccountID());

        if ($account->getId() != $bio->getOwner()->getAccount()->getId()) {
            return new JsonResponse(['error' => 'Diese Biography gehört dir nicht.']);
        }

        $this->eManager->remove($bio);
        $this->eManager->flush();

        return new JsonResponse(['msg' => 'Biographie erfolgreich gelöscht.']);
    }

}
