<?php

namespace App\Service\ParamGenerator\Location;

use App\Entity\Attribute;
use App\Entity\LocationBase;

/**
 * Description of GuildTrainingGenerator
 *
 * @author Draeius
 */
class GuildTrainingGenerator extends LocationParamGeneratorBase {

    public function getParams(LocationBase $location): array {
        return [
            'bonus' => 1,
            'attribute' => Attribute::getName($this->getCharacter()->getGuild()->getBuffedAttribute()),
            'price' => $this->getConfig()->getGuildConfig()->getChangeTrainingPrice()
        ];
    }

}
