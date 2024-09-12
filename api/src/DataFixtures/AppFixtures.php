<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
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
        $manager->persist($mathis);

        $quentin = new User();
        $quentin->setRoles(["ROLE_CHEF"]);
        $quentin->setEmail("quentin.somveille@gmail.com");
        $quentin->setFirstName("Quentin");
        $quentin->setLastName("Somveille");
        $quentin->setPassword($this->passwordHasher->hashPassword($quentin, "quentin"));
        $manager->persist($quentin);

        $kenza = new User();
        $kenza->setRoles(["ROLE_DELIVERY"]);
        $kenza->setEmail("kenza.schuler@gmail.com");
        $kenza->setFirstName("Kenza");
        $kenza->setLastName("Schuler");
        $kenza->setPassword($this->passwordHasher->hashPassword($kenza, "kenza"));
        $manager->persist($kenza);

        $getoar = new User();
        $kenza->setRoles(["ROLE_ADMIN"]);
        $getoar->setEmail("getoar.limani@gmail.com");
        $getoar->setFirstName("Getoar");
        $getoar->setLastName("Limani");
        $getoar->setPassword($this->passwordHasher->hashPassword($getoar, "getoar"));
        $manager->persist($getoar);

        $manager->flush();
    }
}
