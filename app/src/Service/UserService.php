<?php

namespace App\Service;

use App\Entity\User;
use App\Message\AddUser;
use App\Message\UserStatus;
use App\Repository\UserRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\MessageBusInterface;

class UserService
{
    public function __construct(
        private RequestStack        $requestStack,
        private MessageBusInterface $bus,
        private UserRepository      $userRepository
    )
    { }

    public function addUser(InputInterface $input = null): void
    {
        if ($input) {
            $addUser = $this->createAddUserMessageFromInput($input);
        } else {
            $addUser = $this->createAddUserMessageFromRequest();
        }

        $this->bus->dispatch($addUser);
    }

    private function createAddUserMessageFromRequest() : AddUser {
        $request = $this->requestStack->getCurrentRequest();
        //@todo use normalizer instead
        $addUser = new AddUser(
            $request->get('firstName'),
            $request->get('lastName'),
            $request->get('email'),
            $request->get('address'),
            $request->get('active', true),
            $request->get('nip')
        );

        return $addUser;
    }

    private function createAddUserMessageFromInput(InputInterface $input) : AddUser {
        //@todo use normalizer instead
        $addUser = new AddUser(
            $input->getArgument('firstName'),
            $input->getArgument('lastName'),
            $input->getArgument('email'),
            $input->getArgument('address'),
            $input->getArgument('active') ?? true,
            $input->getArgument('nip'),
        );

        return $addUser;
    }

    public function createUserEntityFromMessage(AddUser $addUser) : User {
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

    public function saveUser(AddUser $addUser) : void {
        $this->userRepository->save($this->createUserEntityFromMessage($addUser));
    }

    public function changeUserStatus(int $id, bool $active)
    {
        $this->bus->dispatch(new UserStatus($id, $active));
    }

    public function findUser(int $id): User
    {
        return $this->userRepository->find($id);
    }

    public function findUsers() : array {

        return $this->userRepository->findAll();
    }
}
