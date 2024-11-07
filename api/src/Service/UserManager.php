<?php

namespace App\Service;

use App\Entity\User;
use App\Model\Dto\UserDto;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function entityToDto(User $user): UserDto
    {
        $dto = new UserDto();
        $dto->id = $user->getId();
        $dto->firstName = $user->getFirstName();
        $dto->lastName = $user->getLastName();
        $dto->email = $user->getEmail();
        $dto->role = $user->getRoles()[0];
        $dto->uuid = $user->getUuid()->toRfc4122();
        $dto->phoneNumber = $user->getPhoneNumber();

        return $dto;
    }

    public function dtoToEntity(UserDto $userDto, ?User $user = null): User
    {
        if (empty($user)) {
            $user = new User();
        }

        $user->setEmail($userDto->email);
        $user->setFirstName($userDto->firstName);
        $user->setLastName($userDto->lastName);
        $user->setRoles([$userDto->role]);
        $user->setPhoneNumber($userDto->phoneNumber);

        if ($userDto->password !== null) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $userDto->password));
        }

        return $user;
    }
}