<?php

namespace App\Validator;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotDeletedValidator extends ConstraintValidator
{
    public function __construct(
        private readonly PropertyAccessorInterface $propertyAccessor,
    ) {
    }

    public function validate($value, Constraint $constraint): void
    {
        /* @var NotDeleted $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if ($state = $this->propertyAccessor->getValue($value, 'state')) {
            $stringValue = is_string($state) ? $state : $state->value;
            if (strtolower($stringValue) == 'deleted') {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}
