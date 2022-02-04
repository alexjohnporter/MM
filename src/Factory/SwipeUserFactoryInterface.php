<?php

declare(strict_types=1);

namespace App\Factory;

use App\Message\SwipeUser;

interface SwipeUserFactoryInterface
{
    public function createMessage(string $loggedInUser, string $swipedUser, string $attracted): SwipeUser;
}
