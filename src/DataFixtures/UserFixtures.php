<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // ADMIN USER
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setFirstName('Christian');
        $admin->setLastName('Tubog');
        $admin->setEmail('christiantubog01@gmail.com');
        $admin->setIsVerified(true);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setBirthDate(new \DateTime('1990-01-01'));

        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            'admin123'
        );

        $admin->setPassword($hashedPassword);

        $manager->persist($admin);

        // STAFF USER
        $staff = new User();
        $staff->setUsername('staff');
        $staff->setFirstName('Staff');
        $staff->setLastName('User');
        $staff->setEmail('staff@gmail.com');
        $staff->setIsVerified(true);
        $staff->setRoles(['ROLE_STAFF']);
        $staff->setBirthDate(new \DateTime('1995-01-01'));

        $hashedPassword = $this->passwordHasher->hashPassword(
            $staff,
            'staff123'
        );

        $staff->setPassword($hashedPassword);

        $manager->persist($staff);

        $manager->flush();
    }
}