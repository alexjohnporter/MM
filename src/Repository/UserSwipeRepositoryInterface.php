<?php
declare(strict_types=1);

namespace App\Repository;

interface UserSwipeRepositoryInterface
{
    public function haveUsersPreviouslySwiped(string $loggedInUser, string $swipedUser): bool;

    public function save(
        string $id,
        string $loggedInUser,
        string $swipedUser,
        bool $attracted,
        \DateTime $swipedAt
    ): void;
}