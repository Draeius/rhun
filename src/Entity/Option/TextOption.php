<?php

namespace App\Entity\Option;

use App\Entity\Character;
use App\Entity\Option\Option;
use Doctrine\ORM\EntityManagerInterface;

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
