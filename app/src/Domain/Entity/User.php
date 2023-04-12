<?php

namespace App\Domain\Entity;

interface User
{
    public function getId(): int;
    public function getFirstName(): string;
    public function getLastName(): string;
    public function getEmail(): string;
    public function getAddress(): string;
    public function isActive(): bool;
    public function getNip(): ?string;
}
