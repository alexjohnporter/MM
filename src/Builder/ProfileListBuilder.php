<?php

declare(strict_types=1);

namespace App\Builder;

use App\Entity\User;
use App\Exception\UserDoesNotExistException;
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
        string $gender,
        string $distanceSort,
        int $minAge = 18,
        int $maxAge = 99,
    ): array {
        if (!in_array($gender, User::GENDERS)) {
            $gender = '';
        }

        if (!in_array(strtoupper($distanceSort), ['ASC', 'DESC'])) {
            $distanceSort = 'ASC';
        }

        if (!$this->userRepository->doesUserExist($loggedInUserId)) {
            throw new UserDoesNotExistException($loggedInUserId);
        }

        return $this->userRepository->getUnswipedProfilesForLoggedInUser(
            $loggedInUserId,
            $minAge,
            $maxAge,
            strtoupper($distanceSort),
            $gender
        );
    }
}
