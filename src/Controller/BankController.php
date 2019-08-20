<?php

namespace App\Controller;

use App\Repository\CharacterRepository;
use App\Repository\UserRepository;
use App\Util\Session\RhunSession;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of BankController
 *
 * @author Draeius
 */
class BankController extends BasicController {

    const DRAW_MONEY_ROUTE_NAME = 'money_draw';
    const BANK_MONEY_ROUTE_NAME = 'money_bank';
    const TRANSFER_GEMS_ROUTE_NAME = 'gems_transfer';

    /**
     * @Route("/money/draw/{uuid}", name=BankController::DRAW_MONEY_ROUTE_NAME)
     */
    public function drawMoney(Request $request, $uuid, CharacterRepository $charRepo) {
        $session = new RhunSession();
        $character = $charRepo->find($session->getCharacterID());

        $character->getWallet()->transferGoldToWallet($request->get('gold'));
        $character->getWallet()->transferPlatinToWallet($request->get('platin'));

        $this->getDoctrine()->getManager()->flush($character->getWallet());

        return $this->redirectToWorld($character);
    }

    /**
     * @Route("/money/bank/{uuid}", name=BankController::BANK_MONEY_ROUTE_NAME)
     */
    public function bankMoney(Request $request, $uuid, CharacterRepository $charRepo) {
        $session = new RhunSession();
        $character = $charRepo->find($session->getCharacterID());

        if ($request->get('gold') >= 0) {
            $character->getWallet()->transferGoldToBank($request->get('gold'));
        } else {
            $character->getWallet()->transferGoldToBank($character->getWallet()->getGold());
        }

        if ($request->get('platin') >= 0) {
            $character->getWallet()->transferPlatinToBank($request->get('platin'));
        } else {
            $character->getWallet()->transferPlatinToBank($character->getWallet()->getPlatin());
        }

        $this->getDoctrine()->getManager()->flush($character->getWallet());

        return $this->redirectToWorld($character);
    }

    /**
     * @Route("/gems/transfer/{uuid}", name=BankController::TRANSFER_GEMS_ROUTE_NAME)
     */
    public function transferGems(Request $request, $uuid, CharacterRepository $charRepo, UserRepository $userRepo) {
        $session = new RhunSession();
        $character = $charRepo->find($session->getCharacterID());

        $account = $userRepo->find($session->getAccountID());

        $amount = $request->get('gems');
        if ($amount <= 0) {
            return $this->redirectToWorld($character);
        }
        if ($character->getWallet()->getGems() < $amount) {
            $amount = $character->getWallet()->getGems();
        }
        $character->getWallet()->addGems($amount * -1);
        $account->addGems($amount);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($account);
        $manager->persist($character);
        $manager->flush();

        return $this->redirectToWorld($character);
    }

}
