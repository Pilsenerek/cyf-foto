<?php

namespace App\MessageHandler;

use App\Message\UserStatus;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class UserStatusHandler
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $ur,
    ) {}

    public function __invoke(UserStatus $userStatus)
    {
        $user = $this->ur->find($userStatus->getId());
        $user->setActive($userStatus->isActive());
        $this->em->flush();
    }
}
