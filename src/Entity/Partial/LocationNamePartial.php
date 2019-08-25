<?php

namespace App\Entity\Partial;

/**
 * Description of LocationNamePartial
 *
 * @author Draeius
 */
class LocationNamePartial {

    private $uuid;
    private $title;
    private $id;

    function getTitle() {
        return $this->title;
    }

    function getUuid() {
        return $this->uuid;
    }

    function getId() {
        return $this->id;
    }

    public static function fromData(array $data) {
        $partial = new self();
        $partial->title = $data['title'];
        $partial->uuid = $data['uuid'];
        $partial->id = $data['id'];
        return $partial;
    }

}
