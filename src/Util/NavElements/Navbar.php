<?php

namespace App\Util\NavElements;

use App\Util\NavElements\NavbarElement;
use App\Util\NavElements\NavElement;
use App\Util\NavElements\Navhead;
use Twig\Environment;

/**
 * Builds and displays a navbar for the user
 *
 * @author Draeius
 */
class Navbar {

    /**
     * The only navbar of the page
     * @var Navbar
     */
    private static $navbar;

    /**
     * The list of all elements in this navbar
     * @var NavElement[]
     */
    private $navs = [];

    private function __construct() {
        //private constructor to prevent creating more than one navbar
    }

    /**
     * Adds a NavbarElement to the navbar.
     * @param NavbarElement $nav
     */
    public function addNav(NavbarElement $nav) {
        $this->navs[] = $nav;
    }

    /**
     * Creates a html string containing the navbar
     * @return string
     */
    public function printNavbar(Environment $templating): string {
        if (sizeof($this->navs) < 1) {
            return "";
        }
        //start by sorting the navs
        $this->sortNavs();
        //get a string containing all elements
        $result = $this->printElements($templating);

        return $templating->render('navbar/navbar.html.twig', ['navs' => $result]);
    }

    /**
     * Sorts the navs by their order
     */
    public function sortNavs() {
        uasort($this->navs, function($a, $b) {
            if ($a->getOrder() == $b->getOrder()) {
                return 0;
            }
            return ($a->getOrder() < $b->getOrder()) ? -1 : 1;
        });
    }

    /**
     * Deletes all navs that have been added until now
     */
    public function clearNavs() {
        $this->navs = [];
    }

    /**
     * Returns the curret navbar with all its elements.
     * @return Navbar
     */
    public static function getInstance() {
        if (!self::$navbar) {
            self::$navbar = new Navbar();
        }
        return self::$navbar;
    }

    /**
     * Prints all Elements of this navbar
     * @return string
     */
    private function printElements(Environment $templating) {
        $result = '';
        //every NavElement needs a navhead
        if ($this->navs[0] instanceof Navhead) {
            //first element is a Navhead
            $navhead = $this->navs[0];
        } else {
            //create dummy navhead
            $navhead = new Navhead('');
            $navhead->addNav($this->navs[0]);
        }
        //iterate over all following navs
        for ($index = 1; $index < count($this->navs); $index ++) {
            //check if this element is a navhead
            if ($this->navs[$index] instanceof Navhead) {
                //print earlier navhead and add it to the result
                $result .= $navhead->printElement($templating);
                //start new navhead
                $navhead = $this->navs[$index];
            } else {
                //add the element to the navhead
                $navhead->addNav($this->navs[$index]);
            }
        }
        return $result . $navhead->printElement($templating);
    }

}
