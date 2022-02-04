<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\User;
use App\Message\CreateUser;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function __invoke(CreateUser $message): void
    {
        $user = new User(
            $message->getId(),
            $message->getEmail(),
            $message->getPassword(),
            $message->getName(),
            $message->getGender(),
            $message->getAge()
        );

        $this->userRepository->save($user);
    }
}
