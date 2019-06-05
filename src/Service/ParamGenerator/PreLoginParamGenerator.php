<?php

namespace App\Service\ParamGenerator;

use App\Controller\LoginController;
use App\Controller\PreLoginController;
use App\Query\GetCharacterTotalListQuery;
use App\Query\GetNewestCharacterNameQuery;
use App\Query\GetOnlineCharactersQuery;
use App\Query\GetRaceListQuery;
use App\Repository\AreaRepository;
use App\Service\ConfigService;
use App\Service\DateTimeService;
use App\Service\NavbarFactory\PreLoginNavbarFactory;
use App\Util\Moon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Diese Klasse generiert die benötigten Parameter für das Laden der Seiten.
 *
 * @author Draeius
 */
class PreLoginParamGenerator extends ParamGenerator {

    /**
     *
     * @var PreLoginNavbarFactory
     */
    private $navbarFactory;

    /**
     *
     * @var DateTimeService
     */
    private $timeService;

    /**
     *
     * @var AreaRepository
     */
    private $areaRepo;

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    function __construct(DateTimeService $dtService, PreLoginNavbarFactory $navbarFactory, DateTimeService $timeService, AreaRepository $areaRepo,
            EntityManagerInterface $eManager) {
        parent::__construct($dtService);
        $this->navbarFactory = $navbarFactory;
        $this->timeService = $timeService;
        $this->areaRepo = $areaRepo;
        $this->eManager = $eManager;
    }

    /**
     * Generiert die Parameter für die Startseite
     * @return array
     */
    public function getFrontPageParams(FormInterface $form): array {
        $query = new GetNewestCharacterNameQuery($this->eManager);
        $onlineQuery = new GetOnlineCharactersQuery($this->eManager);
        return array_merge(parent::getBaseParams('', $this->navbarFactory->getFrontPageNavbar()), $this->areaRepo->getDescriptionOfMajorAreas(),
                [
                    'page' => 'preLogin/index',
                    'date' => $this->timeService->getDate(),
                    'time' => $this->timeService->getDateTime('NOW')->format('H:i'),
                    'yearAfterMetroid' => $this->timeService->getYearAfterMetroid(),
                    'kuuTime' => Moon::getKuu()->getMoonPhaseString(),
                    'kunTime' => Moon::getKun()->getMoonPhaseString(),
                    'lastNewPlayer' => $query(),
                    'form' => $form->createView(),
                    'userOnline' => $onlineQuery(),
                    'form_path' => LoginController::ACCOUNT_LOGIN_ROUTE_NAME
        ]);
    }

    /**
     * Generiert die Parameter für das Impressum
     * @return array
     */
    public function getImpressumParams(): array {
        return array_merge(parent::getBaseParams('Impressum', $this->navbarFactory->getDefaultNavbar()),
                [
                    'page' => 'preLogin/impressum'
        ]);
    }

    /**
     * Generiert die Parameter für die Seite mit den Regeln
     * @return array
     */
    public function getServerRulesParams(): array {
        return array_merge(parent::getBaseParams('Die Regeln', $this->navbarFactory->getDefaultNavbar()),
                [
                    'page' => 'preLogin/rules'
        ]);
    }

    /**
     * Generiert die Parameter für die Seite auf der man einen neuen Account erstellt
     * @return array
     */
    public function getNewAccountParams(FormInterface $createForm): array {
        return array_merge(parent::getBaseParams('Account erstellen', $this->navbarFactory->getDefaultNavbar()),
                [
                    'page' => 'preLogin/createUser',
                    'form' => $createForm->createView()
        ]);
    }

    public function getSettingsParams(ConfigService $config): array {
        return array_merge(parent::getBaseParams('Spieleinstellungen', $this->navbarFactory->getDefaultNavbar()),
                [
                    'page' => 'preLogin/settings',
                    'rp_config' => $config->getRpConfig(),
                    'settings' => $config->getSettings()
        ]);
    }

    public function getAboutParams(): array {
        return array_merge(parent::getBaseParams('Über Rhûn', $this->navbarFactory->getDefaultNavbar()),
                [
                    'page' => 'preLogin/about'
        ]);
    }

    public function getRaceListParams(): array {
        $query = new GetRaceListQuery($this->eManager);
        return array_merge(parent::getBaseParams('Rassenübersicht', $this->navbarFactory->getDefaultNavbar()),
                [
                    'page' => 'preLogin/raceList',
                    'races' => $query()
        ]);
    }

    public function getMapParams(): array {
        return array_merge(parent::getBaseParams('Karte von Rhûn', $this->navbarFactory->getDefaultNavbar()),
                [
                    'page' => 'preLogin/preLoginMap'
        ]);
    }

    public function getResetPasswordParams(): array {
        return array_merge(parent::getBaseParams('Passwort zurücksetzen', $this->navbarFactory->getDefaultNavbar()),
                [
                    'page' => 'preLogin/forgotPassword',
                    'form_path' => PreLoginController::RESET_PASSWORD_ROUTE_NAME
        ]);
    }

    public function getListParams(): array {
        $query = new GetCharacterTotalListQuery($this->eManager);
        return array_merge(parent::getBaseParams('Kämpferliste', $this->navbarFactory->getDefaultNavbar()),
                [
                    'page' => 'preLogin/preLoginList',
                    'characters' => $query(),
                    'dateNow' => DateTimeService::getDateTime('NOW')
        ]);
    }

}
