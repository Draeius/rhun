<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping\Column;

/**
 * Fügt eine Spalte hinzu, die angibt, ob das Element das Neueste ist.
 * Indizes müssen im jeweiligen Entity gesetzt werden.
 *
 * @author Draeius
 */
trait EntityIsNewestTrait {

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $newest;

    function isNewest(): bool {
        return $this->newest;
    }

    function setNewest(bool $newest) {
        $this->newest = $newest;
    }

}
