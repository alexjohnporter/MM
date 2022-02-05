<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Model\Coordinates;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $coords = new Coordinates(51.514206, -0.104493);

        $user = new User(
            Uuid::fromString('753080bf-2213-4e2f-bb28-5ba8bba1100c'),
            'foo@bar.com',
            password_hash('foobar', PASSWORD_DEFAULT),
            'Foo Bar',
            User::MALE,
            30,
            $coords->getLat(),
            $coords->getLon()
        );

        $manager->persist($user);

        $manager->flush();
    }
}
