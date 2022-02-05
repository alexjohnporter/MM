<?php

declare(strict_types=1);

namespace App\Builder;

use App\Exception\UserDoesNotExistException;
use App\Repository\UserRepositoryInterface;

class ProfileListBuilder
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function getUnswipedProfilesForLoggedInUser(string $loggedInUserId): array
    {
        if (!$this->userRepository->doesUserExist($loggedInUserId)) {
            throw new UserDoesNotExistException($loggedInUserId);
        }

        return $this->userRepository->getUnswipedProfilesForLoggedInUser($loggedInUserId);
    }
}
