<?php

namespace App\Entity;

use App\Entity\Traits\EntityOwnerTrait;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Beschreibt den Titel eines Charakters.
 *
 * @author Draeius
 * @Entity
 * @Table(name="character_titles")
 */
class Title extends RhunEntity {

    const STANDARD_MALE = 'Fremder';
    const STANDARD_FEMALE = 'Fremde';

    use EntityOwnerTrait;
    
    /**
     *
     * @var Character
     * @ManyToOne(targetEntity="Character", inversedBy="titles")
     */
    protected $owner;

    /**
     * @Column(type="string", length=64) 
     */
    protected $title;

    /**
     * @var boolean
     * @Column(type="boolean", nullable=false)
     */
    protected $isActivated = true;

    /**
     * @var boolean
     * @Column(type="boolean", nullable=false)
     */
    protected $isInFront = true;

    public function getTitle() {
        return $this->title;
    }

    public function getIsActivated() {
        return $this->isActivated;
    }

    public function getIsInFront() {
        return $this->isInFront;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setIsActivated($isActivated) {
        $this->isActivated = $isActivated;
    }

    public function setIsInFront($isInFront) {
        $this->isInFront = $isInFront;
    }

}
