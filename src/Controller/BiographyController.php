<?php

namespace App\Controller;

use App\Entity\Biography;
use App\Entity\Character;
use App\Entity\ServerSettings;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BiographyController extends BasicController {

    /**
     *
     * @var BiographyNavbarFactory
     */
    private $navbarFactory;

    /**
     * @Route("bio/editor", name="bioEditor")
     */
    public function showEditor(Request $request) {
        $builder = $this->get('app.navbar');
        $builder->addNav('Zurück', 'charmanagement');

        $vars = array_merge($this->getBaseVariables($builder, 'Biographie'), [
            'page' => 'default/bioeditor',
            'characters' => $this->getAccount()->getCharacters(),
            'selectedChar' => $request->get('selectedChar')
        ]);

        return $this->render($this->getSkinFile(), $vars);
    }

    /**
     * App\Annotation\Security(needAccount=true)
     * @Route("/bio/{charId}/{uuid}", name="bio", defaults={"uuid"="1"})
     */
    public function showBiography($charId, $uuid) {
        $charRep = $this->getDoctrine()->getRepository('AppBundle:Character');
        /* @var $character Character */
        $character = $charRep->find($charId);

        $settings = $this->getSettings();
        if ($character) {
            $bio = $this->getBiography($character, $settings);
        } else {
            $bio = null;
        }

        $builder = $this->get('app.navbar');
        if ($uuid != '1') {
            $builder->addNav('Zurück zur Kämpferliste', 'list', ['uuid' => $uuid]);
            $builder->addNav('Zurück', 'world', ['uuid' => $uuid, 'locationId' => $this->getCharacter($uuid)->getLocation()->getId()]);
        } else {
            $builder->addNav('Zurück zur Kämpferliste', 'list');
            $builder->addNav('Zurück zur Charakterübersicht', 'charmanagement');
        }

        $entries = $character->getDiaryEntries();
        if (sizeof($entries) > 0 && !$this->getSettings()->getUseMaskedBios()) {
            $builder->addNavhead('Tagebucheinträge');
            foreach ($entries as $diaryEntry) {
                $builder->addNav($diaryEntry->getTitle(), 'diary_show', ['charId' => $charId, 'diaryId' => $diaryEntry->getId(), 'uuid' => $uuid]);
            }
        }

        $vars = array_merge($this->getBaseVariables($builder, 'Biographie'), [
            'page' => 'default/biography',
            'character' => $character,
            'settings' => $settings,
            'biography' => $bio
        ]);

        return $this->render($this->getSkinFile(), $vars);
    }

    /**
     * @Route("/bio_editor/get/data", name="bio_data")
     */
    public function getBiographyData(Request $request) {
        $rep = $this->getDoctrine()->getRepository('AppBundle:Biography');
        $bio = $rep->find($request->get('id'));

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
    public function addBiography(Request $request) {
        $rep = $this->getDoctrine()->getRepository('AppBundle:Character');
        $character = $rep->find($request->get('character'));

        $bio = new Biography();
        $bio->setName($request->get('name'));
        $bio->setOwner($character);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($bio);
        $manager->flush();

        return new JsonResponse(['msg' => 'Bio erfolgreich hinzugefügt']);
    }

    /**
     * @Route("/bio_editor/edit", name="bio_edit")
     */
    public function editBiography(Request $request) {
        $rep = $this->getDoctrine()->getManager()->getRepository('AppBundle:Biography');
        /* @var $bio Biography */
        $bio = $rep->find($request->get('bio'));

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

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($bio);
        if ($bio->getSelected()) {
            foreach ($bio->getOwner()->getBiography() as $bioCheck) {
                $bioCheck->setSelected($bioCheck->getId() == $bio->getId());
                $manager->persist($bioCheck);
            }
        }
        if ($bio->getMaskedBall()) {
            foreach ($bio->getOwner()->getBiography() as $bioCheck) {
                $bioCheck->setMaskedBall($bioCheck->getId() == $bio->getId());
                $manager->persist($bioCheck);
            }
        }
        $manager->flush();

        return new JsonResponse(['msg' => 'Biographie erfolgreich geändert.']);
    }

    /**
     * @Route("/bio_editor/del", name="bio_del")
     */
    public function removeBiography(Request $request) {
        $bio = $this->getDoctrine()->getManager()->getRepository('AppBundle:Biography')->find($request->get('id'));
        $account = $this->getAccount();

        if ($account->getId() != $bio->getOwner()->getAccount()->getId()) {
            return new JsonResponse(['error' => 'Diese Biography gehört dir nicht.']);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($bio);
        $manager->flush();

        return new JsonResponse(['msg' => 'Biographie erfolgreich gelöscht.']);
    }

    private function getBiography(Character $character, ServerSettings $settings) {
        foreach ($character->getBiography() as $bio) {
            if ($settings->getUseMaskedBios()) {
                if ($bio->getMaskedBall()) {
                    return $bio;
                }
            } else {
                if ($bio->getSelected()) {
                    return $bio;
                }
            }
        }
    }

}
