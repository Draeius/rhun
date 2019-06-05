<?php

namespace App\Service;

use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of DateTimeService
 *
 * @author Draeius
 */
class DateTimeService {

    /**
     * The timezone this server is working with
     */
    const TIME_ZONE = 'Europe/Berlin';

    /**
     * The number of game days in one real day.
     */
    const DAYS_PER_REAL_DAY = 4;

    /**
     * Specifies an array which maps the months with a custom name
     */
    const MONTHS = [
        "01" => 'Eismond',
        "02" => 'Maskenmond',
        "03" => 'Saatmond',
        "04" => 'Grasmond',
        "05" => 'Blühmond',
        "06" => 'Wonnemond',
        "07" => 'Honigmond',
        "08" => '"Erntemond',
        "09" => 'Holzmond',
        "10" => 'Weinmond',
        "11" => 'Jagdmond',
        "12" => 'Dustermond'
    ];

    /**
     * An array containing strings describing a time of the day.
     * Because od the fact that a day only has 24 hours, starting with 0, the index my not exceed 23.
     * The strings each descripe the time starting with the lower index, until the next higher one.
     * Example:
     * [0 => "midnight", 1 => "night"]
     * Midnight is from 0:00h until 0:59h.
     */
    const DAYTIME_STRING = [
        0 => 'Mitternacht',
        1 => 'Nacht',
        4 => 'früher Morgen',
        6 => 'Dämmerung',
        8 => 'Morgen',
        12 => 'Mittag',
        15 => 'Nachmittag',
        19 => 'Abend',
        22 => 'Nacht'
    ];

    /**
     *
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     *
     * @var ConfigService
     */
    private $configService;

    public function __construct(EntityManagerInterface $manager, ConfigService $configService) {
        $this->manager = $manager;
        $this->configService = $configService;
    }

    /**
     * Creates a new DateTime object with the specified time
     * The Timezone is specified by the constant TIME_ZONE
     * 
     * @param mixed $time
     * @return DateTime     
     */
    public static function getDateTime($time) {
        return new DateTime($time, new DateTimeZone(self::TIME_ZONE));
    }

    public function timeUntilTomorrow() {
        $time = self::getDateTime("NOW");
        $secs = $time->getTimestamp();

        $time->setTime(0, 0, 0);
        //secs is now seconds from midnight till now
        $secs -= $time->getTimestamp();

        //The seconds one ingame day has
        $secondsIngameDay = ((24 * 60 * 60) / $this->configService->getSettings()->getPhasesPerDay());

        //seconds that have passed in this ingame day
        $secondsToday = $secs % $secondsIngameDay;

        //time until the next ingame day
        return $secondsIngameDay - $secondsToday;
    }

    public function timeUntilNextPhase() {
        return date('G \\S\\t\\u\\n\\d\\e\\n, i \\M\\i\\n\\u\\t\\e\\n', strtotime('1980-01-01 00:00:00 + ' . $this->timeUntilTomorrow() . ' seconds'));
    }

    public function getYearAfterMetroid() {
        $now = self::getDateTime('now');
        $last = $this->manager->getRepository('App:ServerSettings')->find(1)->getLastMeteor();
        $diff = date_diff($last, $now);
        return $diff->format('%y');
    }

    /**
     * Creates a new string representing the current time
     *
     * @see DateTimeUtil::getDate();
     * @param timestamp $time        	
     * @return string
     */
    public function getDate($time = null) {
        $date = self::getDateTime($time);

        $tag = $date->format("d");

        $monat = $date->format("m");
        //local copy because const access seems not to be possible
        $months = self::MONTHS;

        return "$tag. " . $months[$monat];
    }

    /**
     * Konvertiert das gegebene DateTime Objekt in die Spielzeit.
     * Dabei wird die Uhrzeit in Relation zu den Tagen pro Tag gesetzt.
     * Das Datum bleibt dabei unberührt.
     * Bsp:
     * Wenn es an einem Tag 4 Server-Tage gibt,
     * dann wird 15:00 Uhr zu 12:00 Uhr und 15:30 Uhr zu 14:00 Uhr
     *
     * @param DateTime $dateTime
     *        	Die Zeit die umgerechnet werden soll.
     * @return DateTime Das umgerechnete Objekt.
     */
    public function timeToGametime(DateTime $dateTime) {
        // get offset for current day
        $offset = new DateTime();
        $offset->setTime(0, 0, 0);

        // get seconds since midnight
        $timeInSecs = $dateTime->getTimestamp() - $offset->getTimestamp();

        $secsPerPart = (60 * 60 * 24) / $this->configService->getSettings()->getPhasesPerDay();
        $gameTime = ($timeInSecs % $secsPerPart) * $this->configService->getSettings()->getPhasesPerDay();

        // wrap milliseconds in a new DateTime Object
        $gameDateTime = new DateTime();
        $gameDateTime->setTimestamp($gameTime + $offset->getTimestamp());
        return $gameDateTime;
    }

    /**
     * Gibt einen String zurück der die aktuelle Tageszeit beschreibt.
     *
     * @see DateTimeUtil::$DAYTIME_STRING;
     * @return string
     */
    public function getDaytimeString() {
//        $time = DateTimeUtil::timeToGametime(new DateTime(), self::DAYS_PER_REAL_DAY);
        $hour = self::getDateTime('NOW')->format("G");
        for ($index = $hour; $index >= 0; $index --) {
            if (array_key_exists($index, self::DAYTIME_STRING) /* && $hour >= $index */) {
                $string = self::DAYTIME_STRING;
                return $string[$index];
            }
        }
    }

    /**
     * Get the current season. 1 = Winter, 2 = Spring, 3 = Summer, 4 = Fall
     * @return int
     */
    public function getSeason() {
        $date = self::getDateTime('now');
        $compare = $date->format("nd");
        if ($compare < 320) {
            //date is smaller than 320. Thus it is before march 20, thus it is winter.
            return 1;
        }
        if ($compare < 621) {
            //date smaller than june 21 => spring
            return 2;
        }
        if ($compare < 922) {
            //date smaller than september 22 => summer
            return 3;
        }
        if ($compare < 1221) {
            //date smaller than december 22 => fall
            return 4;
        }
        //date greater than december 21 => winter
        return 1;
    }

    public function isSameIngameDay(DateTime $now, DateTime $last) {
        //compare year
        if ($now->format('Y') == $last->format('Y')) {
            //compare day of year
            if ($now->format('z') == $last->format('z')) {
                //compare time
                $indexNow = self::getPhaseIndex($now);
                $indexLast = self::getPhaseIndex($last);
                return $indexNow == $indexLast;
            }
        }
        return false;
    }

    public function setDateToZero(DateTime $date) {
        $offset = self::getDateTime('now');
        $offset->setTimestamp($date->getTimestamp());
        $offset->setTime(0, 0, 0);

        $newDate = self::getDateTime('NOW');
        $newDate->setTimestamp($date->getTimestamp() - $offset->getTimestamp());
        return $newDate;
    }

    public function getPhaseIndex(DateTime $date) {
        $timestamp = self::setDateToZero($date)->getTimestamp();

        //interval = seconds per day / days per real day
        $interval = 86400 / $this->configService->getSettings()->getPhasesPerDay();
        for ($index = 1; $index <= $this->configService->getSettings()->getPhasesPerDay(); $index++) {
            if ($timestamp < ($interval * $index)) {
                return $index - 1;
            }
        }
    }

}
