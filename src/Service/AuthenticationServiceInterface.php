<?php

declare(strict_types=1);

namespace App\Service;

interface AuthenticationServiceInterface
{
    public function authenticateUser(string $email, string $password): string;

    public function isUserAuthenticated(string $userId, string $token): bool;
}
