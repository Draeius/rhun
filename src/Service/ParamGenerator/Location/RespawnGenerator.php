<?php

namespace App\Service\ParamGenerator\Location;

use App\Entity\LocationBase;

/**
 * Description of RespawnGenerator
 *
 * @author Draeius
 */
class RespawnGenerator extends LocationParamGeneratorBase {

    public function getParams(LocationBase $location): array {
        return [];
    }

}
