<?php

namespace App\Form\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of CreateCharacterDTO
 *
 * @author Draeius
 */
class CreateCharacterDTO {

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3, max=32)
     * @App\Form\Validator\UniqueCharacter
     * @var string
     */
    public $name;

    /**
     * @Assert\NotBlank
     * @var string
     */
    public $city;

    /**
     * @Assert\NotBlank
     * @var int
     */
    public $race;

    /**
     * @Assert\NotBlank
     * @var bool
     */
    public $gender;

}
