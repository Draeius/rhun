<?php

namespace App\Util\NavElements;

use Twig\Environment;

/**
 * Description of LinkElement
 *
 * @author Draeius
 */
class LinkElement extends NavElement {

    private $popup;

    public function __construct($title, $target, $popup, $order = 100) {
        parent::__construct($title, $target, $order);
        $this->popup = $popup;
    }

    public function printElement(Environment $templating): string {
        if ($this->popup) {
            $page = 'navbar/popupLink.html.twig';
        } else {
            $page = 'navbar/link.html.twig';
        }
        return $templating->render($page, [
                    'title' => $this->getTitle(),
                    'href' => $this->getTarget()
        ]);
    }

}
