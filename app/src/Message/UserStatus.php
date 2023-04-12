<?php

namespace App\Message;

final class UserStatus
{
    public function __construct(
        private int  $id,
        private bool $active,
    )
    {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

}
