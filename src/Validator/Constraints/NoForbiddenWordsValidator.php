<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Service\ForbiddenWordChecker;

class NoForbiddenWordsValidator extends ConstraintValidator
{
    private $forbiddenWordChecker;

    public function __construct(ForbiddenWordChecker $forbiddenWordChecker)
    {
        $this->forbiddenWordChecker = $forbiddenWordChecker;
    }

    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        if ($this->forbiddenWordChecker->containsForbiddenWord($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', $value)
                ->setParameter('%forbidden_words%', implode(', ', $this->forbiddenWordChecker->getForbiddenWords()))
                ->addViolation();
        }
    }
}