<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        //create admin
        $user = new User();
        $user->setEmail('admin@email.com');
        $user->setRoles(['ROLE_ADMIN']);

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            'password'
        );

        $user->setPassword($hashedPassword);

        $manager->persist($user);
        $manager->flush();

        // create 10 users
        $i = 0;
        do {
            $user = new User();
            $user->setEmail('user' . $i . '@email.com');
            $user->setRoles(['ROLE_USER']);

            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                'password'
            );

            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $manager->flush();
            $i++;
        } while ($i<10);

    }
}
