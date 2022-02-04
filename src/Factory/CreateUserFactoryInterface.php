<?php

declare(strict_types=1);

namespace App\Factory;

use App\Message\CreateUser;

interface CreateUserFactoryInterface
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
    public function createMessage(): CreateUser;
}
