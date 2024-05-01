<?php

namespace App\Service;

use App\Entity\Message;
use App\Repository\MessageRepository;

class MessageService
{
    public function __construct(private MessageRepository $messageRepository)
    {
    }

    public function getMessages(): array
    {
        return $this->messageRepository->findAll();
    }

    public function registerMessage(Message $message): void
    {
        $this->messageRepository->registerMessage($message);
    }
}