<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity\Partial;

/**
 * Description of GuildCharListPartial
 *
 * @author Draeius
 */
class GuildCharListPartial {

    private $tag;
    private $name;

    function getTag() {
        return $this->tag;
    }

    function getName() {
        return $this->name;
    }

    public static function fromData(array $data) {
        $partial = new self();
        $partial->tag = $data['tag'];
        $partial->name = $data['name'];
        return $partial;
    }

}
