<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Message\UserMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class UserMessageHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function __invoke(UserMessage $message): void
    {
        dump($message);
    }
}
