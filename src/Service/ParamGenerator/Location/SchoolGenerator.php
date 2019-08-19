<?php

namespace App\Service\ParamGenerator\Location;

use App\Entity\LocationBase;

/**
 * Description of SchoolGenerator
 *
 * @author Draeius
 */
class SchoolGenerator extends LocationParamGeneratorBase {

    public function getParams(LocationBase $location): array {
        return [
            'character' => $this->getCharacter(),
            'resetPrice' => $this->getConfig()->getRpConfig()->getStatResetPrice()
        ];
    }

}
