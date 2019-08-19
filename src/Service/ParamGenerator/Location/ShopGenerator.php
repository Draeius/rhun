<?php

namespace App\Service\ParamGenerator\Location;

use App\Entity\LocationBase;

/**
 * Description of ShopGenerator
 *
 * @author Draeius
 */
class ShopGenerator extends LocationParamGeneratorBase {

    public function getParams(LocationBase $location): array {
        return [];
    }

}
