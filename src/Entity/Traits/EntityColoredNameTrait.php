<?php

namespace App\Entity\Traits;

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
            throw new InvalidArgumentException('Der Name darf nicht leer oder Null sein.');
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
