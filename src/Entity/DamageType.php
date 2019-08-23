<?php

namespace App\Entity;

use App\Entity\Traits\EntityColoredNameTrait;
use App\Entity\Traits\EntityDescriptionTrait;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * Repräsentiert einen Schadenstyp
 *
 * @author Draeius
 * @Entity
 * @Table(name="damage_types")
 */
class DamageType extends RhunEntity {

    use EntityColoredNameTrait;
    use EntityDescriptionTrait;
}
