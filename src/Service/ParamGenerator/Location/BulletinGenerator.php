<?php

namespace App\Service\ParamGenerator\Location;

use App\Entity\LocationBase;

/**
 * Description of BulletinGenerator
 *
 * @author Draeius
 */
class BulletinGenerator extends LocationParamGeneratorBase {

    public function getParams(LocationBase $location): array {
        $repo = $this->getEntityManager()->getRepository('App:Bulletin');
        return ['bulletins' => $repo->findBy(['location' => $location], ['createdAt' => 'DESC'])];
    }

}
