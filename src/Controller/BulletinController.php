<?php

namespace App\Controller;

use App\Entity\Bulletin;
use App\Repository\CharacterRepository;
use App\Service\ConfigService;
use App\Util\Session\RhunSession;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of BulletinController
 *
 * @author Draeius
 */
class BulletinController extends BasicController {

    /**
     * @Route("bulletin/add/{uuid}", name="bulletin_add")
     */
    public function addBulletin(Request $request, $uuid, CharacterRepository $charRepo, ConfigService $config) {
        $session = new RhunSession();
        $character = $charRepo->find($session->getCharacterID());
        $price = $config->getRpConfig()->getBulletinPrice();

        if (!$character->getWallet()->checkPrice($price)) {
            $session->error('Leider kannst du dir das nicht leisten. So eine Notiz ist aber auch teuer ...');
            return $this->redirectToWorld($uuid);
        }
        $character->getWallet()->subtractPrice($price);

        $bulletin = new Bulletin();
        $bulletin->setAuthor($character);
        $bulletin->setContent($request->get('content'));
        $bulletin->setLocation($character->getLocation());

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($bulletin);
        $manager->persist($character->getWallet());
        $manager->flush();

        return $this->redirectToWorld($character);
    }

}
