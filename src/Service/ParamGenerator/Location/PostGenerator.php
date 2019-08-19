<?php

namespace App\Service\ParamGenerator\Location;

use App\Entity\LocationBase;

/**
 * Description of PostGenerator
 *
 * @author Draeius
 */
class PostGenerator extends LocationParamGeneratorBase {

    public function getParams(LocationBase $location): array {
        return [];
    }

}
