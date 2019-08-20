<?php

namespace App\Controller;

use App\Entity\ColoredName;
use App\Entity\Title;
use App\Util\Session\RhunSession;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of GemShopController
 *
 * @author Draeius
 */
class GemShopController extends BasicController {

    const BUY_NAME_ROUTE_NAME = 'buy_name';
    const BUY_TITLE_ROUTE_NAME = 'buy_title';

    /**
     * @Route("/char/buy/name/{uuid}", name=GemShopController::BUY_NAME_ROUTE_NAME)
     */
    public function buyColoredName(Request $request, $uuid) {
        $character = $this->getCharacter($uuid);
        $price = $this->getServerConfig()->getRpConfig()->getColoredNamePrice();
        if (!$character->getWallet()->checkPrice($price)) {
            $session = new RhunSession($uuid);
            $session->error('Du kannst dir das nicht leisten');
            return $this->redirectToWorld($uuid);
        }
        $character->getWallet()->addPrice($price->multiply(-1));

        $coloredName = $this->getColoredName($character, $request->get('name'));

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($coloredName);
        $manager->flush();

        $character->addColoredName($coloredName);
        $character->setColoredName($coloredName);

        foreach ($character->getColoredNames() as $name) {
            $manager->persist($name);
        }
        $manager->persist($character);
        $manager->flush();

        return $this->redirectToWorld($uuid);
    }

    /**
     * @Route("/char/buy/title/{uuid}", name=GemShopController::BUY_TITLE_ROUTE_NAME)
     */
    public function buyColoredTitle(Request $request, $uuid) {
        $character = $this->getCharacter($uuid);
        $price = $this->getServerConfig()->getRpConfig()->getColoredTitlePrice();
        if (!$character->getWallet()->checkPrice($price)) {
            $session = new RhunSession($uuid);
            $session->error('Du kannst dir das nicht leisten');
            return $this->redirectToWorld($uuid);
        }
        $character->getWallet()->addPrice($price->multiply(-1));

        $title = $this->getColoredTitle($request->get('title'), filter_var($request->get('front'), FILTER_VALIDATE_BOOLEAN));

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($title);
        $manager->flush();

        $character->addTitle($title);
        $character->setTitle($title);

        foreach ($character->getTitles() as $title) {
            $manager->persist($title);
        }
        $manager->persist($character);
        $manager->flush();

        return $this->redirectToWorld($uuid);
    }

    private function getColoredTitle(string $title, bool $inFront) {
        $coloredTitle = new Title();
        $coloredTitle->setTitle($title);
        $coloredTitle->setIsInFront($inFront);
        return $coloredTitle;
    }

    private function getColoredName(string $name): ColoredName {
        $coloredName = new ColoredName();
        $coloredName->setName($name);
        return $coloredName;
    }

}
