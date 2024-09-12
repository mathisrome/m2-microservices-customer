<?php

namespace App\Model\Dto;

use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class UserDto
{
    #[Groups(["user:list", "user:detail"])]
    public ?int $id;

    #[Assert\NotBlank(groups: ["user:create", "user:update"])]
    #[Assert\Email(groups: ["user:create", "user:update"])]
    #[Groups(["user:list", "user:detail"])]
    public string $email;

    #[Assert\NotBlank(groups: ["user:create", "user:update"])]
    #[Groups(["user:list", "user:detail"])]
    public string $firstName;

    #[Assert\NotBlank(groups: ["user:create", "user:update"])]
    #[Groups(["user:list", "user:detail"])]
    public string $lastName;

    #[Assert\NotBlank(groups: ["user:create"])]
    public ?string $password = null;

    #[Assert\NotBlank(groups: ["user:create", "user:update"])]
    public ?string $role = null;
}