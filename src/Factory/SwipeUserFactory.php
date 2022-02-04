<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\UserSwipe;
use App\Exception\UnknownParameterException;
use App\Exception\UserDoesNotExistException;
use App\Message\SwipeUser;
use App\Repository\UserRepositoryInterface;

class SwipeUserFactory implements SwipeUserFactoryInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function createMessage(string $loggedInUser, string $swipedUser, string $attracted): SwipeUser
    {
        $this->checkUserExists($loggedInUser);
        $this->checkUserExists($swipedUser);

        if (!in_array(strtolower($attracted), UserSwipe::ACCEPTED_ATTRACTED_VALUES)) {
            throw new UnknownParameterException('attracted', $attracted);
        }

        return new SwipeUser(
            $loggedInUser,
            $swipedUser,
            UserSwipe::ATTRACTED_VALUES_MAPPING[strtolower($attracted)]
        );
    }

    private function checkUserExists(string $id): void
    {
        if (!$this->userRepository->doesUserExist($id)) {
            throw new UserDoesNotExistException($id);
        }
    }
}
