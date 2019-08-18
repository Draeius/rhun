<?php

namespace App\Form\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Description of UniqueCharacterConstraint
 *
 * @author Draeius
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
class UniqueCharacter extends Constraint {

    public $message = 'Der Name ist bereits vergeben.';

}
