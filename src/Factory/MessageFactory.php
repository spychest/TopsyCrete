<?php

namespace App\Factory;

use App\Entity\Message;

class MessageFactory
{
    public function createMessage(array $messageData): Message
    {
        $isValid = $this->validate($messageData);

        if($isValid === false){
            throw new \Exception('Message data must contains pseudo and content');
        }

        return (new Message())
            ->setSender($messageData['pseudo'])
            ->setContent($messageData['content'])
            ->setSendAt(new \DateTimeImmutable());
    }

    private function validate(array $messageData): bool
    {
        return isset($messageData['pseudo'])
            && isset($messageData['content']);
    }
}