<?php

namespace App\MessageHandler;

use App\Domain\UserRepository;
use App\Entity\User;
use App\Message\AddUser;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class AddUserHandler
{

    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function __invoke(AddUser $addUser)
    {
        $this->userRepository->save($this->createUserEntityFromMessage($addUser));
    }

    private function createUserEntityFromMessage(AddUser $addUser): \App\Domain\Entity\User
    {
        //@todo use normalizer instead
        $newUser = new User();
        $newUser->setActive($addUser->isActive());
        $newUser->setAddress($addUser->getAddress());
        $newUser->setEmail($addUser->getEmail());
        $newUser->setFirstName($addUser->getFirstName());
        $newUser->setLastName($addUser->getLastName());
        $newUser->setNip($addUser->getNip());

        return $newUser;
    }
}
