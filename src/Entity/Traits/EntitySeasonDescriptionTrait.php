<?php

namespace App\Entity\Traits;

use App\Util\Season;
use Doctrine\ORM\Mapping\Column;

/**
 * Ein Trait, dass es dem Entity ermöglicht, vier verschiedene Beschreibungen zu haben. Je nach Jahreszeit.
 *
 * @author Draeius
 */
trait EntitySeasonDescriptionTrait {

    /**
     * Die Beschreibung für den Frühling
     * @var string
     * @Column(type="text", nullable=false)
     */
    protected $descriptionSpring = '';

    /**
     * Die Beschreibung für den Sommer
     * @var string
     * @Column(type="text", nullable=true)
     */
    protected $descriptionSummer = null;

    /**
     * Die Beschreibung für den Herbst
     * @var string
     * @Column(type="text", nullable=true)
     */
    protected $descriptionFall = null;

    /**
     * Die Beschreibung für den Winter
     * @var string
     * @Column(type="text", nullable=true)
     */
    protected $descriptionWinter = null;

    /**
     * Gibt die Beschreibung für die aktuelle Jahreszeit zurück.
     * Wenn es keine Beschreibung für die aktuelle Jahreszeit gibt, wird die für den Frühling zurückgegeben.
     * @return string
     */
    public function currentDescription(): string {
        $season = Season::getSeasonByDate();
        switch ($season) {
            case Season::SUMMER:
                if ($this->descriptionSummer) {
                    return $this->descriptionSummer;
                }
                break;
            case Season::SEASON_FALL:
                if ($this->descriptionFall) {
                    return $this->descriptionFall;
                }
                break;
            case Season::SEASON_WINTER:
                if ($this->descriptionWinter) {
                    return $this->descriptionWinter;
                }
                break;
        }
        return $this->descriptionSpring;
    }

    function getDescriptionSpring() {
        return $this->descriptionSpring;
    }

    function getDescriptionSummer() {
        return $this->descriptionSummer;
    }

    function getDescriptionFall() {
        return $this->descriptionFall;
    }

    function getDescriptionWinter() {
        return $this->descriptionWinter;
    }

    function setDescriptionSpring($descriptionSpring) {
        $this->descriptionSpring = $descriptionSpring;
    }

    function setDescriptionSummer($descriptionSummer) {
        $this->descriptionSummer = $descriptionSummer;
    }

    function setDescriptionFall($descriptionFall) {
        $this->descriptionFall = $descriptionFall;
    }

    function setDescriptionWinter($descriptionWinter) {
        $this->descriptionWinter = $descriptionWinter;
    }

}
