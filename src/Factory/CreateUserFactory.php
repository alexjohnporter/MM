<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;
use App\Message\CreateUser;
use App\Model\Coordinates;
use Faker\Factory;
use Faker\Provider\Internet;
use Faker\Provider\ka_GE\Person;
use Symfony\Component\Uid\Uuid;

class CreateUserFactory implements CreateUserFactoryInterface
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

        //these are some random coords dotted about London
        $allowedCoords = [
            new Coordinates(51.520199, -0.120467),
            new Coordinates(51.513349, -0.132430),
            new Coordinates(51.508521, -0.142301),
            new Coordinates(51.544145, -0.202576),
            new Coordinates(51.558695, -0.073959)
        ];

        return new CreateUser(
            (string)Uuid::v4(),
            $faker->email,
            password_hash('foobar', PASSWORD_DEFAULT),
            $faker->name,
            $faker->randomElement([User::FEMALE, User::MALE, User::PREFER_NOT_TO_SAY]),
            rand(18, 99),
            $allowedCoords[rand(0, 4)]
        );
    }
}
