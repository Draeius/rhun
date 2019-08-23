<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping\Column;

/**
 * Ein Trait, das einem Entity ermöglicht eine Beschreibung zu haben.
 *
 * @author Draeius
 */
trait EntityDescriptionTrait {

    /**
     * A description of this area
     * @var string
     * @Column(type="text", nullable=true)
     */
    protected $description;

    function getDescription(): ?string {
        return $this->description;
    }

    function setDescription(string $description) {
        $this->description = $description;
    }

}
