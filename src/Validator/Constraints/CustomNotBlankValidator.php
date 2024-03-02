<?php
// src/Validator/Constraints/CustomNotBlankValidator.php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CustomNotBlankValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
