<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Util\NavElements;

use Twig\Environment;

/**
 * Description of Nav
 *
 * @author Draeius
 */
class Nav extends NavElement {

    private $params;

    public function __construct($title, $target, $params = [], $order = 100) {
        parent::__construct($title, $target, $order);
        $this->params = $params;
    }

    public function getParams() {
        return $this->params;
    }

    public function printElement(Environment $templating): string {
        $result = $templating->render('navbar/nav.html.twig', [
            'title' => $this->getTitle(),
            'target' => $this->getTarget(),
            'params' => $this->params
        ]);
        return $result;
    }

}
