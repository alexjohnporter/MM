<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;
    public function doesUserExist(string $id): bool;
    public function getUnswipedProfilesForLoggedInUser(
        string $loggedInUserId,
        int $minAge,
        int $maxAge,
        string $distanceSort,
        string $gender,
    ): array;
    public function isUserAuthenticated(string $userId, string $token): bool;
    public function getUserById(string $userId): User;
    public function getUserByEmail(string $email): User;
}
