<?php

namespace App\Service\ParamGenerator;

use App\Service\DateTimeService;
use App\Service\NavbarService;
use App\Util\Session\RhunSession;

/**
 * Eine Klasse, die Basisparameter erstellt, die auf jeder Seite benötigt werden.
 *
 * @author Draeius
 */
class ParamGenerator {

    /**
     *
     * @todo move to config
     */
    public static $JS_VERSION = '0.0.1';

    /**
     *
     * @var RhunSession
     */
    private $session;

    /**
     *
     * @var DateTimeService
     */
    private $dtService;

    public function __construct(DateTimeService $dtService) {
        $this->session = new RhunSession();
        $this->dtService = $dtService;
    }

    public function getBaseParams(string $pageTitle, NavbarService $navbar) {
        return [
            'jsversion' => self::$JS_VERSION,
            'logd_version' => 'rhun-0.9.7',
            'uuid' => $this->session->getTabIdentifier(),
            'account' => null,
            'character' => null,
            'nextPhase' => $this->dtService->timeUntilNextPhase(),
            'error' => '',
            'log' => '',
            'userOnline' => false,
            'charsPresent' => false,
            'pagetitle' => $pageTitle,
            'navbar' => $navbar->build(),
            'showLinks' => false,
            'showMails' => false,
            'forum_link' => 'forum.rhun-logd.de',
            'wiki_link' => 'rhun-logd.de/wiki',
            'mail_link' => 'mail_show'
        ];
    }

}
