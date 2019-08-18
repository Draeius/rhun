<?php

namespace App\Service;

use App\Util\Session\RhunSession;
use App\Util\TabIdentification\TabIdentifier;

/**
 * Ein Service der Aufgaben zur generierung von TabIdentifiern beinhaltet.
 *
 * @author Draeius
 */
class TabIdentifierService {

    /**
     *
     * @var RhunSession
     */
    private $session;

    public function __construct() {
        $this->session = new RhunSession();
    }

    /**
     * Generiert einen neuen, bisher nicht genutzen TabIdentifier.
     * 
     * @return TabIdentifier
     */
    public function getUnusedTabIdentifier(): TabIdentifier {
        $continue = true;
        do {
            $rand = substr(md5(microtime()), rand(0, 26), 6);
            if (!is_numeric($rand) && !$this->session->isUsedID($rand)) {
                $continue = false;
            }
        } while ($continue);
        return new TabIdentifier($rand);
    }

}
