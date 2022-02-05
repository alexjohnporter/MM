<?php

declare(strict_types=1);

namespace App\Builder;

use App\Entity\User;
use App\Exception\UserDoesNotExistException;
use App\Model\Filter;
use App\Model\Sort;
use App\Repository\UserRepositoryInterface;

class ProfileListBuilder
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    //todo - could use a Strategy to get the Sort/Filters
    public function getUnswipedProfilesForLoggedInUser(
        string $loggedInUserId,
        int $minAge = 18,
        int $maxAge = 99,
        string | null $gender = '',
    ): array {
        if (!in_array($gender, User::GENDERS)) {
            $gender = '';
        }

        if (!$this->userRepository->doesUserExist($loggedInUserId)) {
            throw new UserDoesNotExistException($loggedInUserId);
        }

        return $this->userRepository->getUnswipedProfilesForLoggedInUser($loggedInUserId, $minAge, $maxAge, $gender);
    }
}
