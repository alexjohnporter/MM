<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;
use App\Message\CreateUser;
use Faker\Factory;
use Faker\Provider\Internet;
use Faker\Provider\ka_GE\Person;
use Symfony\Component\Uid\Uuid;

class UserMessageFactory implements UserMessageFactoryInterface
{
    /**
     * I keep as much logic away from the controller as possible
     *  following a hexagonal architecture approach.
     *
     * In a real app, this function would get params from the Request,
     * validate and do any additional logic required.
     *
     * In this case, I've kept it simple by using Faker to generate new Users
     */
    public function createMessage(): CreateUser
    {
        $faker = Factory::create();

        return new CreateUser(
            Uuid::v4(),
            $faker->email,
            $faker->password,
            $faker->name,
            $faker->randomElement([User::FEMALE, User::MALE, User::PREFER_NOT_TO_SAY]),
            rand(18, 99)
        );
    }
}
