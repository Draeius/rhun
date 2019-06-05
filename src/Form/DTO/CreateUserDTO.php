<?php

namespace App\Form\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ein DTO um Daten für einen neuen Account zu übergeben.
 *
 * @author Draeius
 */
class CreateUserDTO {

    /**
     *
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(min="4", max="64")
     */
    public $username;

    /**
     *
     * @var string 
     * @Assert\NotBlank
     * @Assert\Length(min="5")
     */
    public $password;

    /**
     * 
     * @var string
     * @Assert\NotBlank
     * @Assert\Email
     */
    public $email;

    /**
     * 
     * @var DateTimeInterface
     */
    public $birthday;

}
