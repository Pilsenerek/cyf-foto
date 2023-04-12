<?php

namespace App\Command;

use App\Domain\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(
    name: 'user:get',
    description: 'Get user|users',
)]
class UserGetCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository,
        private SerializerInterface $serializer
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::OPTIONAL, 'User ID (All users when not provided)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        if ($input->getArgument('id')) {
            $user = $this->userRepository->find($input->getArgument('id'));
            $io->block($this->serializer->serialize($user, 'json'));
        } else {
            $users = $this->userRepository->findAll();
            $io->block($this->serializer->serialize($users, 'json'));
        }

        return Command::SUCCESS;
    }
}
