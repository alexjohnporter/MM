<?php

declare(strict_types=1);

namespace App\Builder;

interface ProfileListBuilderInterface
{
    public function getUnswipedProfilesForLoggedInUser(
        string $loggedInUserId,
        string $gender,
        string $distanceSort,
        int $minAge = 18,
        int $maxAge = 99,
    ): array;
}
