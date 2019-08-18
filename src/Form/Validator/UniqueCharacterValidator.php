<?php

namespace App\Form\Validator;

use App\Repository\CharacterRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Description of UniqueCharacterValidator
 *
 * @author Draeius
 */
class UniqueCharacterValidator extends ConstraintValidator {

    /**
     *
     * @var CharacterRepository
     */
    private $charRepo;

    public function __construct(CharacterRepository $charRepo) {
        $this->charRepo = $charRepo;
    }

    public function validate($value, Constraint $constraint) {
        $existingCharacter = $this->charRepo->findOneBy([
            'name' => $value
        ]);
        if (!$existingCharacter) {
            return;
        }
        $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
    }

}
