<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;
    public function doesUserExist(string $id): bool;
    public function getUnswipedProfilesForLoggedInUser(string $loggedInUserId): array;
}
