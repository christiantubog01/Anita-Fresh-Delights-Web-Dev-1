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
        
                // Admin user
        $admin = new User();
        $admin->setUsername('admin01');
        $admin->setFirstName('Christian');
        $admin->setLastName('Tubog Fixture');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setBirthDate(new \DateTime('1990-01-01'));

        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            'admin123');
        $admin->setPassword($hashedPassword);

        $manager->persist($admin);

        // Staff user
        $staff = new User();
        $staff->setUsername('staff');
        $staff->setFirstName('Staff');
        $staff->setLastName('User');
        $staff->setRoles(['ROLE_STAFF']);
        $staff->setBirthDate(new \DateTime('1995-01-01'));

        $hashedPassword = $this->passwordHasher->hashPassword(
            $staff,
            'staff123');
        $staff->setPassword($hashedPassword);

        $manager->persist($staff);


        

        $manager->flush();
    }
}
