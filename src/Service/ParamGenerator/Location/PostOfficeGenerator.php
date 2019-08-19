<?php

namespace App\Service\ParamGenerator\Location;

use App\Entity\LocationBase;

/**
 * Description of PostOfficeGenerator
 *
 * @author Draeius
 */
class PostOfficeGenerator extends LocationParamGeneratorBase {

    public function getParams(LocationBase $location): array {
        return [
            'inventory' => $this->getCharacter()->getInventory()
        ];
    }

}
