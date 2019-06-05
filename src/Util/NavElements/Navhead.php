<?php

namespace App\Util\NavElements;

use Twig\Environment;

/**
 * Creates a headline that can have subelements
 *
 * @author Draeius
 */
class Navhead extends NavbarElement {

    private $navs = array();

    function __construct(string $title, int $order = 100) {
        parent::__construct($title, $order);
    }

    function printElement(Environment $templating): string {
        $navs = '';
        /* @var $nav Nav */
        foreach ($this->navs as $nav) {
            $navs .= $nav->printElement($templating);
        }

        return $templating->render('navbar/navhead.html.twig', [
                    'title' => $this->getTitle(),
                    'navs' => $navs
        ]);
    }

    function addNav(NavElement $nav) {
        $this->navs[] = $nav;
    }

}
