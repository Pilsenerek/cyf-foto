<?php

namespace App\MessageHandler;

use App\Domain\UserRepository;
use App\Message\UserStatus;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class UserStatusHandler
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function __invoke(UserStatus $userStatus)
    {
        $this->userRepository->changeStatus($userStatus->getId(), $userStatus->isActive());
    }
}
