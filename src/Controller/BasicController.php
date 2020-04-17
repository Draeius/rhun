<?php

namespace App\Controller;

use App\Doctrine\UuidEncoder;
use App\Entity\Character;
use App\Entity\Message;
use App\Entity\ServerSettings;
use App\Entity\ShortNews;
use App\Service\DateTimeService;
use App\Service\SkinService;
use App\Util\Session\RhunSession;
use App\Util\Skin;
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

    function __construct(SkinService $skinService) {
        $this->skinService = $skinService;
    }

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

    public function getServerSettings(): ServerSettings {
        $manager = $this->getDoctrine()->getManager();
        $settings = $manager->find('App:ServerSettings', 1);
        if (!$settings) {
            $settings = new ServerSettings();
            $manager->persist($settings);
            $manager->flush();
        }
        return $settings;
    }

    public function getSkinFile() {
        $session = new RhunSession();
        if (!$session->getAccountID()) {
            return $this->skinService->getDefaultSkin();
        }
        $account = $this->getDoctrine()->getRepository('App:User')->find($session->getAccountID());
        return $this->skinService->getSkinByName($account->getTemplate());
    }

    public function redirectToWorld(Character $char) {
        if (!$this->tabIdentifier || !$this->tabIdentifier->hasIdentifier()) {
            return $this->redirectToRoute(AccountManagementController::ACCOUNT_MANAGEMENT_ROUTE_NAME);
        }
        $encoder = new UuidEncoder();
        return $this->redirectToRoute(WorldController::STANDARD_WORLD_ROUTE_NAME,
                        ['uuid' => $this->tabIdentifier->getIdentifier(), 'locationId' => $encoder->encode($char->getLocation()->getUuid())]);
    }

    public function addShortNews(string $content, Character $char) {
        $news = new ShortNews();
        $news->setCharacter($char);
        $news->setContent($content);
        $news->setCreatedAtValue(DateTimeService::getDateTime('NOW'));

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($news);
        $manager->flush();
    }

    public function sendSystemPN(string $subject, string $content, Character $char) {
        $message = new Message();
        $message->setAddressee($char);
        $message->setContent($content);
        $message->setImportant(true);
        $message->setSender(null);
        $message->setSubject($subject);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($message);
        $manager->flush();
    }

}
