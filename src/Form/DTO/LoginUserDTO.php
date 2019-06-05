<?php

namespace App\Form\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of LoginUserDTO
 *
 * @author Draeius
 */
class LoginUserDTO {

    /**
     *
     * @var string
     * @Assert\NotBlank
     */
    public $username = '';

    /**
     *
     * @var string 
     * @Assert\NotBlank
     */
    public $password = '';

}
