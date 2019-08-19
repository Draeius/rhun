<?php

namespace App\Service\ParamGenerator\Location;

use App\Entity\LocationBase;

/**
 * Description of BankGenerator
 *
 * @author Draeius
 */
class BankGenerator extends LocationParamGeneratorBase {

    public function getParams(LocationBase $location): array {
        return ['wallet' => $this->getCharacter()->getWallet()];
    }

}
