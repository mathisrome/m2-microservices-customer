<?php

namespace App\Message;

final class UserMessage
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $email,
        public readonly string $firstName,
        public readonly string $lastName,
    )
    {
    }
}
