<?php

use Symfony\Component\Validator\Validation;

function validateData($data, $constraints)
{
    $validator = Validation::createValidator();
    $violations = [];

    foreach ($constraints as $field => $fieldConstraints) {
        foreach ($fieldConstraints as $constraint) {
            $fieldValue = $data[$field] ?? null;
            $fieldViolations = $validator->validate($fieldValue, $constraint);

            if ($fieldViolations->count() > 0) {
                $violations[$field] = $fieldViolations;
            }
        }
    }

    return $violations;
}
