<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\InvalidPasswordException;
use App\Repository\UserRepositoryInterface;
use App\Util\TokenGeneratorInterface;

/**
 * In a real production setting, I'd use something like a JWT or oAuth2
 */
class AuthenticationService implements AuthenticationServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private TokenGeneratorInterface $tokenGenerator
    ) {
    }

    public function authenticateUser(string $email, string $password): string
    {
        $user = $this->userRepository->getUserByEmail($email);

        if (!password_verify($password, $user->getPassword())) {
            throw new InvalidPasswordException();
        }

        $token = $this->tokenGenerator->generateToken();

        $user->authenticateUser($token);

        $this->userRepository->save($user);

        return $token;
    }

    public function isUserAuthenticated(string $userId, string $token): bool
    {
        return $this->userRepository->isUserAuthenticated($userId, $token);
    }
}
