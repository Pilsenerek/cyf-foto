<?php

namespace App\Command;

use App\Message\AddUser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'user:add',
    description: 'Add new user',
)]
class UserAddCommand extends Command
{
    public function __construct(private MessageBusInterface $bus)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('firstName', InputArgument::REQUIRED, 'First name')
            ->addArgument('lastName', InputArgument::REQUIRED, 'Last name')
            ->addArgument('email', InputArgument::REQUIRED, 'Email')
            ->addArgument('address', InputArgument::REQUIRED, 'Address')
            ->addArgument('nip', InputArgument::OPTIONAL, 'NIP number')
            ->addArgument('active', InputArgument::OPTIONAL, 'Is active')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->bus->dispatch($this->createAddUserMessageFromInput($input));
        $io->success('Adding new user is dispatched!');

        return Command::SUCCESS;
    }

    private function createAddUserMessageFromInput(InputInterface $input): AddUser
    {
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
}
