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
            Uuid::fromString('753080bf-2213-4e2f-bb28-5ba8bba1100c'),
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
