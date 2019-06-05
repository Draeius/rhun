<?php

namespace App\Entity\Partial;

/**
 * Description of LocationNamePartial
 *
 * @author Draeius
 */
class LocationNamePartial {

    private $title;

    function getTitle() {
        return $this->title;
    }

    public static function fromData(array $data) {
        $partial = new self();
        $partial->title = $data['title'];
        return $partial;
    }

}
