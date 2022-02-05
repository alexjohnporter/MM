<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class UserDoesNotExistException extends \Exception
{
    public function __construct(string $userId)
    {
        $message = sprintf('User does not exist with ID: %s', $userId);

        parent::__construct($message, JsonResponse::HTTP_NOT_FOUND, null);
    }
}
