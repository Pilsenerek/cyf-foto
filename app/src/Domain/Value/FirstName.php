<?php

namespace App\Domain\Value;

class FirstName
{
    public function __construct(private string $firstName)
    {
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }
}
