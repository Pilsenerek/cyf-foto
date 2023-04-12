<?php

namespace App\MessageHandler;

use App\Message\UserStatus;
use App\Service\UserService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class UserStatusHandler
{
    public function __construct(
        private UserService $userService,
    ) {}

    public function __invoke(UserStatus $userStatus)
    {
        $this->userService->saveUserStatus($userStatus);
    }
}
