<?php

namespace App\Service\ParamGenerator\Location;

use App\Entity\LocationBase;

/**
 * Description of GraveyardGenerator
 *
 * @author Draeius
 */
class GraveyardGenerator extends LocationParamGeneratorBase {

    public function getParams(LocationBase $location): array {
        $dead = $this->getEntityManager()->getRepository('App:Character')->findBy(['dead' => true], ['name' => 'ASC']);
        return ['chars' => $dead];
    }

}
