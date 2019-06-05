<?php

namespace App\Util\NavElements;

use Twig\Environment;

/**
 * Baseclass for all elements of a navbar
 *
 * @author Draeius
 */
abstract class NavbarElement {

    private $title;
    private $order;

    function __construct(string $title, int $order = 100) {
        $this->title = $title;
        $this->order = $order;
    }

    function getTitle() {
        return $this->title;
    }

    function setTitle(string $title) {
        $this->title = $title;
    }

    public function getOrder() {
        return $this->order;
    }

    public function setOrder($order) {
        $this->order = $order;
    }

    abstract function printElement(Environment $templating): string;
}
