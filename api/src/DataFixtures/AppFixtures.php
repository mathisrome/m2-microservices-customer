<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Message\UserMessage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private MessageBusInterface $messageBus,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $mathis = new User();
        $mathis->setRoles(["ROLE_CUSTOMER"]);
        $mathis->setEmail("mathis.rome@icloud.com");
        $mathis->setFirstName("Mathis");
        $mathis->setLastName("Rome");
        $mathis->setPassword($this->passwordHasher->hashPassword($mathis, "mathis"));
        $mathis->setPhoneNumber('0601020304');
        $manager->persist($mathis);
        $this->messageBus->dispatch(
            new UserMessage(
                $mathis->getUuid()->toRfc4122(),
                $mathis->getEmail(),
                $mathis->getFirstName(),
                $mathis->getLastName(),
                $mathis->getPhoneNumber()
            )
        );

        $quentin = new User();
        $quentin->setRoles(["ROLE_CHEF"]);
        $quentin->setEmail("quentin.somveille@gmail.com");
        $quentin->setFirstName("Quentin");
        $quentin->setLastName("Somveille");
        $quentin->setPassword($this->passwordHasher->hashPassword($quentin, "quentin"));
        $quentin->setPhoneNumber('0601020304');
        $manager->persist($quentin);
        $this->messageBus->dispatch(
            new UserMessage(
                $quentin->getUuid()->toRfc4122(),
                $quentin->getEmail(),
                $quentin->getFirstName(),
                $quentin->getLastName(),
                $quentin->getPhoneNumber()
            )
        );

        $kenza = new User();
        $kenza->setRoles(["ROLE_DELIVERY"]);
        $kenza->setEmail("kenza.schuler@gmail.com");
        $kenza->setFirstName("Kenza");
        $kenza->setLastName("Schuler");
        $kenza->setPassword($this->passwordHasher->hashPassword($kenza, "kenza"));
        $kenza->setPhoneNumber('0601020304');
        $manager->persist($kenza);
        $this->messageBus->dispatch(
            new UserMessage(
                $kenza->getUuid()->toRfc4122(),
                $kenza->getEmail(),
                $kenza->getFirstName(),
                $kenza->getLastName(),
                $kenza->getPhoneNumber(),
            )
        );

        $getoar = new User();
        $getoar->setRoles(["ROLE_ADMIN"]);
        $getoar->setEmail("getoar.limani@gmail.com");
        $getoar->setFirstName("Getoar");
        $getoar->setLastName("Limani");
        $getoar->setPassword($this->passwordHasher->hashPassword($getoar, "getoar"));
        $getoar->setPhoneNumber('0601020304');
        $manager->persist($getoar);
        $this->messageBus->dispatch(
            new UserMessage(
                $getoar->getUuid()->toRfc4122(),
                $getoar->getEmail(),
                $getoar->getFirstName(),
                $getoar->getLastName(),
                $getoar->getPhoneNumber(),
            )
        );

        $manager->flush();
    }
}
