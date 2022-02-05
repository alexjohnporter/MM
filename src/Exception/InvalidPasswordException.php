<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;

class InvalidPasswordException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Invalid password', JsonResponse::HTTP_FORBIDDEN, null);
    }
}
