<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Post\Post;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_Fr');

        // My user
        $user = new User();
        $user->setEmail('emilien@symblog.fr')
            ->setFirstName('Emilien')
            ->setPassword(
                $this->hasher->hashPassword($user, 'password')
            );

        $manager->persist($user);

        for ($i = 0; $i < 9; $i++) {
            $user = new User();
            $user->setEmail($faker->email())
                ->setLastName($faker->lastName())
                ->setFirstName($faker->firstName())
                ->setPassword(
                    $this->hasher->hashPassword($user, 'password')
                );

            $manager->persist($user);
        }

        $manager->flush();
    }
}
