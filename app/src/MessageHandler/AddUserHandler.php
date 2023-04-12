<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Message\AddUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class AddUserHandler
{

    public function __construct(private EntityManagerInterface $em) {}

    public function __invoke(AddUser $addUser)
    {
        $newUser = new User();
        $newUser->setActive($addUser->isActive());
        $newUser->setAddress($addUser->getAddress());
        $newUser->setEmail($addUser->getEmail());
        $newUser->setFirstName($addUser->getFirstName());
        $newUser->setLastName($addUser->getLastName());
        $newUser->setNip($addUser->getNip());

        $this->em->persist($newUser);
        $this->em->flush();
    }
}
