<?php

namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NoForbiddenWords extends Constraint
{
    public $message = 'The string "%string%" contains forbidden words: %forbidden_words%';
}