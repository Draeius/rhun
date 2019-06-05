<?php

namespace App\Entity\Partial;

/**
 * Enthält teile der Daten einer Rasse.
 *
 * @author Draeius
 */
class RacePartial {

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var string
     */
    private $description;

    /**
     *
     * @var string
     */
    private $city;

    function getName() {
        return $this->name;
    }

    function getDescription() {
        return $this->description;
    }

    function getCity() {
        return $this->city;
    }

    public static function fromData(array $data): RacePartial {
        $partial = new RacePartial();
        $partial->city = $data['city'];
        $partial->description = $data['description'];
        $partial->name = $data['name'];
        return $partial;
    }

}
