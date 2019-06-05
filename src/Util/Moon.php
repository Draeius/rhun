<?php

namespace App\Util;

use App\Service\DateTimeService;

/**
 * Represents a Moon and contains methods to manipulate it.
 *
 * @author Draeius
 */
class Moon {

    /**
     * Number of orbits of the moon Kuu in one year
     */
    const KUU_ORBITS_PER_YEAR = 14;

    /**
     * Number of orbits of the moon Kun in one year
     */
    const KUN_ORBITS_PER_YEAR = 10;

    /**
     * Specifies descriptions of the moonphases
     */
    const NEG_MOONPHASES = [
        'nicht zu sehen',
        'kaum zu sehen',
        'sichelförmig und abnehmend',
        'halb und abnehmend',
        'kaum erkennbar abnehmend',
        'voll',
    ];
    const POS_MOONPHASES = [
        'nicht zu sehen',
        'kaum zu sehen',
        'sichelförmig und zunehmend',
        'halb und zunehmend',
        'fast voll und zunehmend',
        'voll'
    ];

    private $indicator;
    private $orbits;

    private function __construct(int $orbits) {
        $this->orbits = $orbits;
        $this->indicator = $this->calculateIndicator();
    }

    public function getIndicator() {
        return $this->indicator;
    }

    public function getOrbits() {
        return $this->orbits;
    }

    public function getMoonPhaseString() {
        $indicator = $this->indicator < 0 ? $this->indicator * -1 : $this->indicator;
        $phases = $this->indicator >= 0 ? self::POS_MOONPHASES : self::NEG_MOONPHASES;

        if ($indicator > 0.95) {
            return $phases[5];
        }
        if ($indicator > 0.6) {
            return $phases[4];
        }
        if ($indicator > 0.45) {
            return $phases[3];
        }
        if ($indicator > 0.1) {
            return $phases[2];
        }
        if ($indicator > 0.01) {
            return $phases[1];
        } else {
            return $phases[0];
        }
    }

    private function calculateIndicator() {
        $time = DateTimeService::getDateTime("now");
        $day = (int) $time->format("z");
        $daysPerOrbit = 365 / $this->orbits;
        $indicator = (($day - $daysPerOrbit) % ($daysPerOrbit * 2)) / $daysPerOrbit - 1; // Indicator becomes at most 2
        return $indicator;
    }

    public static function getKuu() {
        return new Moon(self::KUU_ORBITS_PER_YEAR);
    }

    public static function getKun() {
        return new Moon(self::KUN_ORBITS_PER_YEAR);
    }

}
