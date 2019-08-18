<?php

namespace App\Entity;

use App\Entity\Traits\EntityColoredNameTrait;
use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\EntitySeasonDescriptionTrait;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\MappedSuperclass;
use ReflectionClass;

/**
 * Description of LocationBase
 *
 * @author Draeius
 * @HasLifecycleCallbacks
 * @MappedSuperclass
 */
abstract class LocationBase extends RhunEntity {

    use EntityIdTrait;
    use EntityColoredNameTrait;
    use EntitySeasonDescriptionTrait;

    /**
     * If this location is for adults only
     * @var bool
     * @Column(type="boolean")
     */
    protected $adult;

    function getAdult() {
        return $this->adult;
    }

    function setAdult($adult) {
        $this->adult = $adult;
    }

    public function getDataArray(): array {
        $fields = [];
        $refclass = new ReflectionClass($this->getClassName());
        foreach ($refclass->getProperties() as $property) {
            $name = $property->name;
            if ($property->class == $refclass->name) {
                $fields[$name] = $this->$name;
            }
        }
        return $fields;
    }

    protected abstract function getClassName(): string;
}
