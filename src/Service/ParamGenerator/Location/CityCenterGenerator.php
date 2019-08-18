<?php

namespace App\Service\ParamGenerator\Location;

use App\Entity\Location;
use App\Entity\LocationBase;
use App\Service\DateTimeService;
use App\Util\Moon;

/**
 * Description of CityCenterGenerator
 *
 * @author Draeius
 */
class CityCenterGenerator extends LocationParamGeneratorBase {

    public function getParams(LocationBase $location): array {
        if (!($location instanceof Location)) {
            return [];
        }
        /* @var $location Location */
        return [
            'date' => $this->getDtService()->getDate(),
            'year' => $this->getDtService()->getYearAfterMetroid(),
            'time' => DateTimeService::getDateTime('now')->format('G:i'),
            'timeString' => $this->getDtService()->getDaytimeString(),
            'phaseKuu' => Moon::getKuu()->getMoonPhaseString(),
            'phaseKun' => Moon::getKun()->getMoonPhaseString(),
            'shortNews' => $this->getEntityManager()->getRepository('App:ShortNews')->findBy([], ['createdAt' => 'DESC'], 2)
        ];
    }

}
