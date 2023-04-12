<?php

namespace App\Service;

use App\Entity\User;
use App\Message\AddUser;
use App\Message\UserStatus;
use App\Repository\UserRepository;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\MessageBusInterface;

class UserService
{

    private FilesystemAdapter $cache;

    public function __construct(
        private RequestStack        $requestStack,
        private MessageBusInterface $bus,
        private UserRepository      $userRepository
    )
    {
        $this->cache = new FilesystemAdapter();
    }

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

    public function saveUserStatus(UserStatus $userStatus) : void {
        $user = $this->userRepository->find($userStatus->getId());
        $user->setActive($userStatus->isActive());
        $this->userRepository->save($user);
    }

    public function changeUserStatus(int $id, bool $active)
    {
        $this->bus->dispatch(new UserStatus($id, $active));
    }

    public function findUser(int $id): User
    {
        $cachedUser = $this->cache->getItem($this->getCacheKey($id));
        if (!$cachedUser->isHit()) {
            $user = $this->userRepository->find($id);
            $cachedUser->set($user);
            $this->cache->save($cachedUser);
        } else {
            $user = $cachedUser->get();
        }

        return $user;
    }

    public function findUsers() : array {

        return $this->userRepository->findAll();
    }

    private function getCacheKey(int $userId = null): string
    {
        $chunks[] = 'user';
        if ($userId) {
            $chunks[] = $userId;
        }

        return join('_', $chunks);
    }
}
