<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

class UserDoesNotExistException extends \Exception
{
    public function __construct(string $userId)
    {
        $message = sprintf('User does not exist with ID: %s', $userId);

        parent::__construct($message, 0, null);
    }
}
