<?php

namespace App\Message;

final class AddUser
{

    public function __construct(
        private string $firstName,
        private string $lastName,
        private string $email,
        private string $address,
        private bool $active = true,
        private ?string $nip = null
    )
    {
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return string|null
     */
    public function getNip(): ?string
    {
        return $this->nip;
    }

}
