<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service\NavbarFactory;

use App\Controller\AccountManagementController;
use App\Controller\LogoutController;
use App\Controller\PreLoginController;
use App\Doctrine\UuidEncoder;
use App\Entity\Character;
use App\Entity\User;
use App\Query\GetNewMessageCountQuery;
use App\Service\NavbarService;
use App\Util\Session\RhunSession;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of AccountMngmtNavbarFactory
 *
 * @author Draeius
 */
class AccountMngmtNavbarFactory {

    /**
     *
     * @var NavbarService
     */
    private $navbarService;

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    function __construct(NavbarService $navbarService, EntityManagerInterface $eManager) {
        $this->navbarService = $navbarService;
        $this->eManager = $eManager;
    }
    
    public function buildEmptyNavbar(){
        return $this->navbarService;
    }

    public function buildNavbar(User $user) {
        $builder = $this->navbarService;
        if ($user->isBanned() || !$user->getValidated()) {
            $this->addBannedNavs();
        } else {
            $this->addStandardNavs($user);
            $this->addModNavs($user);
        }
        $builder->addNavhead('Sonstiges')
                ->addNav('Karte', PreLoginController::PRE_LOGIN_MAP_ROUTE_NAME)
//                ->addNav('Regeln', 'rules')
                ->addNav('Kämpferliste', PreLoginController::FIGHTER_LIST_ROUTE_NAME)
                ->addPopupLink('Discord', 'https://discord.gg/Yu8tYxF')
                ->addPopupLink('Wiki', 'http://www.rhun-logd.de/wiki')
                ->addNavhead('Abmelden')
                ->addNav('Abmelden', LogoutController::LOGOUT_ACCOUNT_ROUTE_NAME, []);
        return $builder;
    }

    public function buildMailNavbar(?Character $character) {
        $session = new RhunSession();
        if (!$session->getTabIdentifier()->hasIdentifier()) {
            $this->navbarService->addNav('Zurück', 'acct_mnmgt');
        } else {
            $encoder = new UuidEncoder();
            $this->navbarService->addNav('Zurück', 'world', [
                'uuid' => $session->getTabIdentifier()->getIdentifier(),
                'locationId' => $encoder->encode($character->getLocation()->getUuid())
            ]);
        }
        return $this->navbarService;
    }

    private function addBannedNavs() {
        $this->navbarService
                ->addNav('Bio Editor (gesperrt)', AccountManagementController::ACCOUNT_MANAGEMENT_ROUTE_NAME)
                ->addNav('Tagebuch schreiben (gesperrt)', AccountManagementController::ACCOUNT_MANAGEMENT_ROUTE_NAME)
                ->addNav('Taubenschlag (gesperrt)', AccountManagementController::ACCOUNT_MANAGEMENT_ROUTE_NAME);
    }

    private function addStandardNavs(User $user) {
        $query = new GetNewMessageCountQuery($this->eManager);
        $count = $query($user);

        $this->navbarService
//                ->addNav('Bio Editor', 'bioEditor')
//                ->addNav('Tagebuch schreiben', 'diary_editor')
                ->addNav('Taubenschlag' . ($count > 0 ? ' (Neu: ' . $count . ')' : ''), 'mail_show');
    }

    private function addModNavs(User $account) {
        $builder = $this->navbarService;
//        if ($account->getUserRole()->getWriteMotd()) {
//            $builder->addNavhead('Motd')
//                    ->addNav('Motd-Creator', 'motd_editor', []);
//        }
        if ($account->getUserRole()->getEditWorld()) {
            $builder->addNavhead('Editoren')
                    ->addNav('Areale', 'area_index')
                    ->addNav('Buchthemen', 'book_theme_index')
                    ->addNav('Bücher', 'book_index')
                    ->addNav('Rassen', 'race_index');
        }
//        if ($account->getUserRole()->getEditMonster()) {
//            $builder->addNavhead('Editoren')
//                    ->addNav('Monster', 'admin_monster', [])
//                    ->addNav('Aktivitäten', 'admin_activity', []);
//        }
//        if ($account->getUserRole()->getReviewPosts()) {
//            $builder->addNavhead('Mod')
//                    ->addNav('Postübersicht', 'post-list');
//        }
    }

}
