<?php

namespace App\Util\House;

use App\Entity\Attribute;
use App\Entity\BurglarAlarm;
use App\Entity\Character;
use App\Service\ConfigService;
use App\Util\Config\HouseConfig;

/**
 * Description of Intrusion
 *
 * @author Draeius
 */
class Intrusion {

    private $alarms;

    /**
     *
     * @var HouseConfig
     */
    private $config;

    public function __construct($alarms, ConfigService $config) {
        $this->alarms = $alarms;
        $this->config = $config->getHouseConfig();
    }

    /**
     * Gibt an, ob der Einbruch ein Efolg war oder nicht
     * 
     * @param Character $character
     * @return boolean|BurglarAlarm true, wenn der Einbruch ein Erfolg war. False, wenn der Einbruch gescheitert ist, aber kein Alarm ausgelöst wurde.
     * Den entsprechenden Alarm, wenn einer ausgelöst wurde.
     */
    public function isSuccess(Character $character) {
        $dexDice = $character->getAttributeDice(Attribute::DEXTERITY);
        if (!$dexDice->checkThreshold($this->config->getIntrusionChance())) {
            return false;
        }
        /* @var $value BurglarAlarm */
        foreach ($this->alarms as $value) {
            $dice = $character->getAttributeDice($value->getAttribute());
            if (!$dice->checkThreshold($value->getDifficulty())) {
                return $value;
            }
        }
        return true;
    }

}
