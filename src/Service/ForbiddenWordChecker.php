<?php

namespace App\Service;

class ForbiddenWordChecker
{
    private $forbiddenWords;

    public function __construct(array $forbiddenWords)
    {
        $this->forbiddenWords = $forbiddenWords;
    }

    public function getForbiddenWords(): array
    {
        return $this->forbiddenWords;
    }

    public function containsForbiddenWord(string $comment): bool
    {
        foreach ($this->forbiddenWords as $word) {
            if (stripos($comment, $word) !== false) {
                return true;
            }
        }
        return false;
    }
}