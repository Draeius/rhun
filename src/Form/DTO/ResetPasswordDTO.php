<?php

namespace App\Form\DTO;

/**
 * Description of ResetPasswordDTO
 *
 * @author Draeius 
 */
class ResetPasswordDTO {

    /**
     *
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(min="4", max="64")
     */
    public $username;

}
