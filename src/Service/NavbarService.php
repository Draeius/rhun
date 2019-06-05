<?php

namespace App\Service;

use App\Util\NavElements\Navbar;
use App\Util\NavElements\LinkElement;
use App\Util\NavElements\Nav;
use App\Util\NavElements\NavbarElement;
use App\Util\NavElements\Navhead;
use Symfony\Component\Stopwatch\Stopwatch;
use Twig\Environment;

/**
 * Description of NavbarService
 *
 * @author Draeius
 */
class NavbarService {

    private $templating;
    private $navbar;
    private $order = 0;

    /**
     *
     * @var Stopwatch
     */
    private $stopwatch;

    public function __construct(Environment $templating, Stopwatch $stopwatch) {
        $this->templating = $templating;
        $this->stopwatch = $stopwatch;
        $this->navbar = Navbar::getInstance();
    }

    public function addNavhead($title): NavbarService {
        return $this->addNavbarElement(new Navhead($title, $this->order));
    }

    public function addNav($title, $target, $params = []): NavbarService {
        return $this->addNavbarElement(new Nav($title, $target, $params, $this->order));
    }

    public function addPopupLink($title, $href): NavbarService {
        return $this->addNavbarElement(new LinkElement($title, $href, true, $this->order));
    }

    public function addLink($title, $href): NavbarService {
        return $this->addNavbarElement(new LinkElement($title, $href, false, $this->order));
    }

//    public function addCommandNav($title, $target, $params): NavbarService {
//        return $this->addNavbarElement(new CommandElement($title, $target, $params, $this->order));
//    }

    public function build(): string {
        $this->stopwatch->start('navbar.build');

        $result = $this->navbar->printNavbar($this->templating);

        $this->stopwatch->stop('navbar.build');
        return $result;
    }

    public function clear() {
        $this->navbar->clearNavs();
    }

//    public function createForLocation(EntityManager $manager, LocationEntity $loc, Character $character, $uuid) {
//        if ($loc == null) {
//            return;
//        }
//        $this->stopwatch->start('navbar.create');
//
//        $sort = Criteria::create();
//        $sort->orderBy(Array(
//            'navbarIndex' => Criteria::ASC
//        ));
//        $navs = $loc->getNavs()->matching($sort);
//
//        if ($character->getCurrentHP() <= 0 && $character->getDead() && !($loc->getArea()->getDeadAllowed())) {
//            $this->addNav('In die Unterwelt', 'underworld_tp', ['uuid' => $uuid]);
//        } else {
//            $this->fillNavbar($manager, $loc, $character, $uuid, $navs);
//        }
//        $this->stopwatch->stop('navbar.create');
//        return $this;
//    }
//    private function addActivityNavs(EntityManager $manager, $location, $uuid) {
//        $activityArray = $manager->getRepository('AppBundle:Activity')->findByLocation($location);
//        foreach ($activityArray as $key => $value) {
//            $activityArray[$key] = [];
//            $activityArray[$key]['activity'] = $value;
//            $activityArray[$key]['path'] = 'activity_exec';
//            $activityArray[$key]['params'] = [
//                'uuid' => $uuid,
//                'id' => $value->getId()
//            ];
//        }
//        $this->addInteractionNavs($activityArray, 'Aktivitäten');
//    }
//    private function addQuestNavs(EntityManager $manager, $location, Character $character, $uuid) {
//        $questArray = $manager->getRepository('AppBundle:Quest')->findByLocation($location);
//        if (!is_array($questArray)) {
//            $questArray = array($questArray);
//        }
//        foreach ($questArray as $key => $value) {
//            if ($character->hasFinishedQuest($value) || !$value->getAvailable()) {
//                unset($questArray[$key]);
//            } else {
//                $questArray[$key] = [];
//                $questArray[$key]['activity'] = $value;
//                $questArray[$key]['path'] = 'quest_complete';
//                $questArray[$key]['params'] = [
//                    'uuid' => $uuid,
//                    'questId' => $value->getId()
//                ];
//            }
//        }
//        $this->addInteractionNavs($questArray, 'Quests');
//    }
//
//    private function addInteractionNavs($array, $navheadLabel) {
//        if (!$array) {
//            return;
//        }
//        $this->addNavhead($navheadLabel);
//        if (!is_array($array)) {
//            $array = array($array);
//        }
//        foreach ($array as $element) {
//            $this->addNav($element['activity']->getNavLabel(), $element['path'], $element['params']);
//        }
//    }

    private function addNavbarElement(NavbarElement $nav): NavbarService {
        $this->navbar->addNav($nav);
        $this->order++;
        return $this;
    }

//    private function fillNavbar(EntityManager $manager, LocationEntity $loc, Character $character, $uuid, $navs) {
//        $locationService = new LocationService();
//        $creationService = new NavCreationService();
//        foreach ($navs as $nav) {
//            if ($nav->getTargetLocation() === null || $locationService->isAllowedLocation($nav->getTargetLocation(), $character)) {
//                $this->order = $nav->getNavbarIndex();
//                $this->navbar->addNav($creationService->createNavElement($nav, $this->order, $uuid));
//                $this->order ++;
//            }
//        }
//        $this->addActivityNavs($manager, $loc, $uuid);
//        $this->addQuestNavs($manager, $loc, $character, $uuid);
//    }
}
