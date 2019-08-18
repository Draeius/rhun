<?php

namespace App\Entity;

use App\Entity\Traits\EntityColoredNameTrait;
use App\Entity\Traits\EntityDescriptionTrait;
use App\Entity\Traits\EntityIdTrait;
use App\Repository\AreaRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Table;

/**
 * Describes an Area. An Area is a collection of Locations
 *
 * @author Draeius
 * @Entity(repositoryClass="AreaRepository")
 * @Table(name="areas")
 * @HasLifecycleCallbacks
 */
class Area extends RhunEntity {

    use EntityIdTrait;
    use EntityColoredNameTrait;
    use EntityDescriptionTrait;

    /**
     * The city this area belongs to
     * @var string
     * @Column(type="string", length=64)
     */
    protected $city;

    /**
     *
     * @var boolean
     * @Column(type="boolean")
     */
    protected $deadAllowed;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $raceAllowed = false;

    public function getCity() {
        return $this->city;
    }

    public function getDeadAllowed() {
        return $this->deadAllowed;
    }

    function getRaceAllowed() {
        return $this->raceAllowed;
    }

    function setRaceAllowed(bool $raceAllowed) {
        $this->raceAllowed = $raceAllowed;
    }

    public function setDeadAllowed(bool $deadAllowed) {
        $this->deadAllowed = $deadAllowed;
    }

    public function setCity($city) {
        $this->city = $city;
    }

}
