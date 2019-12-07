<?php

namespace App\Entity\Option;

use App\Entity\Character;
use App\Entity\Option\Option;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * 
 * @Entity
 * @Table(name="options_text")
 */
class TextOption extends Option {

    public function execute(EntityManagerInterface $eManager, Character $character) {
        //nothing to do here...
    }

}
