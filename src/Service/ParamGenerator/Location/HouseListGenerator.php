<?php

namespace App\Service\ParamGenerator\Location;

use App\Entity\LocationBase;

/**
 * Description of HouseListGenerator
 *
 * @author Draeius
 */
class HouseListGenerator extends LocationParamGeneratorBase {

    public function getParams(LocationBase $location): array {
        $rep = $this->getEntityManager()->getRepository('App:House');
        return [
            'houses' => $rep->findBy(['location' => $location], ['title' => 'ASC']),
            'buildPrice' => $this->getConfig()->getHouseConfig()->getHouseBuildPrice()
        ];
    }

}
