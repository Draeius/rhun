<?php

namespace App\Form\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of PersistRaceDTO
 *
 * @author Draeius
 */
class PersistRaceDTO {

    /**
     *
     * @var number 
     * @Assert\Type(type="numeric")
     */
    protected $id;
    
    /**
     * 
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(max=64)
     */
    protected $name;

    /**
     * 
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(max=128)
     */
    protected $coloredName;

    /**
     * The city in which this race lives
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(max=64)
     */
    protected $city;

    /**
     * 
     * @var Location
     * @Assert\NotBlank
     * @Assert\Type(type="numeric")
     */
    protected $deathLocation;

    /**
     * 
     * @var Location
     * @Assert\NotBlank
     * @Assert\Type(type="numeric")
     */
    protected $location;

    /**
     * The race's description
     * @var string
     * @Assert\NotBlank
     */
    protected $description;

    /**
     * The Areas which this race may visit.
     * @var Area[]
     * @Assert\NotBlank
     */
    protected $allowedAreas;

    /**
     *
     * @var boolean
     * @Assert\NotBlank
     * @Assert\Type(type="bool")
     */
    protected $allowed = true;

}
