<?php

declare(strict_types=1);

namespace App\Exception;

class UserAlreadySwipedException extends \Exception
{
    public function __construct(string $loggedInUser, string $swipedUser)
    {
        $message = sprintf("User (%s) has already swiped User(%s)", $loggedInUser, $swipedUser);

        parent::__construct($message, 0, null);
    }
}
