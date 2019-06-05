<?php

namespace App\Util\NavElements;

/**
 * Ein einzelnes Navigationselement in der Navbar
 *
 * @author Draeius
 */
abstract class NavElement extends NavbarElement {

    private $target;

    public function __construct($title, $target, $order = 100) {
        parent::__construct($title, $order);
        $this->target = $target;
    }

    public function getTarget() {
        return $this->target;
    }

    public function getPopup() {
        return $this->popup;
    }

    public function setTarget($target) {
        $this->target = $target;
    }

    public function setPopup($popup) {
        $this->popup = $popup;
    }

}
