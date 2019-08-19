<?php

namespace App\Service\ParamGenerator\Location;

use App\Entity\LocationBase;

/**
 * Description of GemShopGenerator
 *
 * @author Draeius
 */
class GemShopGenerator extends LocationParamGeneratorBase {

    public function getParams(LocationBase $location): array {
        $config = $this->getConfig()->getRpConfig();
        return [
            'priceColoredName' => $config->getColoredNamePrice(),
            'priceColoredTitle' => $config->getColoredTitlePrice()
        ];
    }

}
