<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Util;

use App\Annotation\Security;
use App\Query\CheckAccountSecurityQuery;
use App\Query\CheckCharacterSecurityQuery;
use App\Util\Session\RhunSession;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of RouteSecurityChecker
 *
 * @author Draeius
 */
class RouteSecurityChecker {

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    /**
     *
     * @var Security
     */
    private $annotation;

    public function __construct(EntityManagerInterface $eManager, Security $annotation) {
        $this->eManager = $eManager;
        $this->annotation = $annotation;
    }

    public function checkSecurity(): bool {
        if (!$this->annotation->needAccount) {
            return true;
        }

        $session = new RhunSession();
        $accountQuery = new CheckAccountSecurityQuery($this->eManager);
        if (!$session->getAccountID() || !$accountQuery($session->getAccountID(), $this->annotation)) {
            return false;
        }

        if ($this->annotation->needCharacter) {
            $characterQuery = new CheckCharacterSecurityQuery($this->eManager);
            if (!$session->getCharacterID() || !$characterQuery($session->getCharacterID(), $session->getAccountID())) {
                return false;
            }
        }
        return true;
    }

}
