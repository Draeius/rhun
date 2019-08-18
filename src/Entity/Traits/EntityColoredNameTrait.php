<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping\Column;

/**
 * Description of EntityColoredNameTrait
 *
 * @author Draeius
 */
trait EntityColoredNameTrait {

    /**
     * 
     * @var string
     * @Column(type="string", length=64, nullable=false)
     */
    protected $name = '';

    /**
     * 
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $coloredName;

    function hasColoredName(): bool {
        return $this->coloredName != null;
    }

    function getName(): string {
        return $this->name;
    }

    function getColoredName(): ?string {
        return $this->coloredName;
    }

    function setName(string $name) {
        if ($name == null || trim($name) == '') {
            $name = 'Leerer Name';
        }
        $this->name = $name;
    }

    function setColoredName(string $coloredName) {
        if (trim($coloredName) == '') {
            $this->coloredName = null;
        } else {
            $this->coloredName = $coloredName;
        }
    }

}
