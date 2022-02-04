<?php

declare(strict_types=1);

namespace App\Message;

class SwipeUser
{
    public function __construct(
        private string $loggedInUser,
        private string $swipedUser,
        private bool $attracted
    ) {
    }

    public function getLoggedInUser(): string
    {
        return $this->loggedInUser;
    }

    public function getSwipedUser(): string
    {
        return $this->swipedUser;
    }

    public function isAttracted(): bool
    {
        return $this->attracted;
    }

    public function swipedAt(): \DateTime
    {
        return new \DateTime('now');
    }
}
