<?php

namespace App\Command;

use App\Service\MessageService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

CONST EXPORT_PATH = "./public/exports";

#[AsCommand(
    name: 'app:message:export',
    description: 'Add a short description for your command',
)]
class MessageExportCommand extends Command
{
    public function __construct(private readonly MessageService $messageService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $messages = $this->messageService->getMessages();

        if(!file_exists(EXPORT_PATH)){
            mkdir(EXPORT_PATH);
        }

        $jsonPath = EXPORT_PATH."/messages.json";

        file_put_contents($jsonPath, json_encode($messages, JSON_PRETTY_PRINT));


        $io->success('File created');

        return Command::SUCCESS;
    }
}
