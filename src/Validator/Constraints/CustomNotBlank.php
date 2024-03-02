<?php
// src/Validator/Constraints/CustomNotBlank.php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CustomNotBlank extends Constraint
{
    public string $message = 'This value should not be blank.';
}