<?php

namespace App\Controller;

use App\Util\TabIdentification\TabIdentifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Basisklasse für alle Controller dieses Projekts.
 *
 * @author Draeius
 */
class BasicController extends AbstractController {

    /**
     *
     * @var Skin
     */
    private static $skin = null;

    /**
     *
     * @var SkinService
     */
    private $skinService;

    /**
     * Der Tabidentifier sorgt dafür, dass der Controller die unterschiedlichen Tabs unterscheiden
     * kann und man sich mit mehreren Charakteren im gleichen Browser anmelden kann.
     * Wenn er null ist, ist noch kein Charakter angemeldet.
     * 
     * @var TabIdentifier
     */
    private $tabIdentifier = null;

    /**
     * Gibt den TabIdentifier zurück, der in dieser Anfrage mitgeschickt wurde.
     * 
     * @return TabIdentifier
     */
    public function getTabIdentifier(): TabIdentifier {
        return $this->tabIdentifier;
    }

    /**
     * Setzt den TabIdentifier.
     * 
     * @param TabIdentifier $tabIdentifier Der neue TabIdentifier.
     */
    public function setTabIdentifier(TabIdentifier $tabIdentifier): void {
        $this->tabIdentifier = $tabIdentifier;
    }

    public function getSkinFile() {
        return 'skins/cobalt/cobalt.html.twig';
    }

}
