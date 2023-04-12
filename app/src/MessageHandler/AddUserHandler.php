<?php

namespace App\MessageHandler;

use App\Message\AddUser;
use App\Service\UserService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class AddUserHandler
{

    public function __construct(private UserService $userService) {}

    public function __invoke(AddUser $addUser)
    {
        $this->userService->saveUser($addUser);
    }
}
