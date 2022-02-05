<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\UserSwipe;
use App\Exception\UserAlreadySwipedException;
use App\Message\SwipeUser;
use App\Repository\UserSwipeRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
class SwipeUserHandler
{
    public function __construct(
        private UserSwipeRepository $userSwipeRepository
    ) {
    }

    public function __invoke(SwipeUser $message): void
    {
        if (
            $this->userSwipeRepository->haveUsersPreviouslySwiped(
                $message->getLoggedInUser(),
                $message->getSwipedUser()
            )
        ) {
            throw new UserAlreadySwipedException($message->getLoggedInUser(), $message->getSwipedUser());
        }

//        dump($message); exit;

        $this->userSwipeRepository->save(
            (string)Uuid::v4(),
            $message->getLoggedInUser(),
            $message->getSwipedUser(),
            $message->isAttracted(),
            $message->swipedAt()
        );
    }
}
