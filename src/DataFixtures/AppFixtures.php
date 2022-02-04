<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User(
            Uuid::v4(),
            'foo@bar.com',
            'foobar',
            'Foo Bar',
            User::MALE,
            30
        );

        $manager->persist($user);

        $manager->flush();
    }
}
