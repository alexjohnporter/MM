<?php

declare(strict_types=1);

namespace App\Controller;

use App\Builder\ProfileListBuilder;
use App\Entity\User;
use App\Exception\UnknownParameterException;
use App\Exception\UserAlreadySwipedException;
use App\Exception\UserDoesNotExistException;
use App\Factory\CreateUserFactoryInterface;
use App\Factory\SwipeUserFactoryInterface;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

class UserController extends AbstractController
{
    public function __construct(
        private CreateUserFactoryInterface $createUserFactory,
        private SwipeUserFactoryInterface $swipeUserFactory,
        private ProfileListBuilder $profileListBuilder,
        private MessageBusInterface $messageBus
    ) {
    }

    public function hello(): JsonResponse
    {
        return new JsonResponse('Hello muzmatch!');
    }

    public function create(): JsonResponse
    {
        $userMessage = $this->createUserFactory->createMessage();

        $this->dispatchMessage($userMessage);

        return new JsonResponse([
            'message' => 'User created successfully',
            'code' => JsonResponse::HTTP_OK,
            'data' => $userMessage->jsonSerialize()
        ]);
    }

    public function profiles(string $loggedInUserId): JsonResponse
    {
        $userList = $this->profileListBuilder->getUnswipedProfilesForLoggedInUser($loggedInUserId);

        return new JsonResponse([
            'message' => 'Unswiped users fetched successfully',
            'code' => JsonResponse::HTTP_OK,
            'data' => $userList
        ]);
    }

    public function swipe(string $loggedInUser, string $swipedUser, string $attracted): JsonResponse
    {
        try {
            $this->dispatchMessage(
                $this->swipeUserFactory->createMessage($loggedInUser, $swipedUser, $attracted)
            );
        } catch (UserDoesNotExistException $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
                'code' => JsonResponse::HTTP_NOT_FOUND,
                'data' => []
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (UnknownParameterException | UserAlreadySwipedException $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
                'code' => JsonResponse::HTTP_PRECONDITION_FAILED,
                'data' => []
            ], JsonResponse::HTTP_PRECONDITION_FAILED);
        }

        return new JsonResponse([
            'message' => 'User swiped successfully',
            'code' => JsonResponse::HTTP_OK,
            'data' => []
        ]);
    }

    private function dispatchMessage(mixed $message): void
    {
        try {
            $this->messageBus->dispatch($message);
        } catch (HandlerFailedException $e) {
            while ($e instanceof HandlerFailedException) {
                /** @var \Throwable $e */
                $e = $e->getPrevious();
            }

            throw $e;
        }
    }
}
