<?php

namespace App\Entity\Traits;

/**
 * Description of EntityHealthTrait
 *
 * @author Draeius
 */
trait EntityHealthTrait {

    /**
     * The maximum hp
     * @var int 
     * @Column(type="integer") 
     */
    protected $maxHP = 100;

    function getMaxHP() {
        return $this->maxHP;
    }

    function setMaxHP($maxHP) {
        $this->maxHP = $maxHP;
    }

}
