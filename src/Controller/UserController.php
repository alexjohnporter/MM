<?php

declare(strict_types=1);

namespace App\Controller;

use App\Builder\ProfileListBuilder;
use App\Exception\InvalidPasswordException;
use App\Exception\UnknownParameterException;
use App\Exception\UserAlreadySwipedException;
use App\Exception\UserDoesNotExistException;
use App\Factory\CreateUserFactoryInterface;
use App\Factory\SwipeUserFactoryInterface;
use App\Service\AuthenticationServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserController extends AbstractController
{
    public function __construct(
        private CreateUserFactoryInterface $createUserFactory,
        private SwipeUserFactoryInterface $swipeUserFactory,
        private ProfileListBuilder $profileListBuilder,
        private AuthenticationServiceInterface $authenticationService,
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

    public function login(Request $request): JsonResponse
    {
        $email = $request->get('email');
        $password = $request->get('password');

        if (!$email || !$password) {
            return new JsonResponse([
                'message' => 'Missing parameters',
                'code' => JsonResponse::HTTP_BAD_REQUEST,
                'data' => []
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $token = $this->authenticationService->authenticateUser($email, $password);
        } catch (UserDoesNotExistException | InvalidPasswordException $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'data' => []
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'message' => 'User authenticated successfully',
            'code' => JsonResponse::HTTP_OK,
            'data' => [
                'token' => $token
            ]
        ]);
    }

    public function profiles(Request $request, string $loggedInUser): JsonResponse
    {
        try {
            $this->authenticateUser($request, $loggedInUser);

            $userList = $this->profileListBuilder->getUnswipedProfilesForLoggedInUser($loggedInUser);
        } catch (UserDoesNotExistException $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
                'code' => JsonResponse::HTTP_NOT_FOUND,
                'data' => []
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (AuthenticationException $e) {
            return new JsonResponse([
                'message' => 'User is not authenticated',
                'code' => JsonResponse::HTTP_FORBIDDEN,
                'data' => []
            ]);
        }

        return new JsonResponse([
            'message' => 'Unswiped users fetched successfully',
            'code' => JsonResponse::HTTP_OK,
            'data' => $userList
        ]);
    }

    public function swipe(
        Request $request,
        string $loggedInUser,
        string $swipedUser,
        string $attracted
    ): JsonResponse {
        try {
            $this->authenticateUser($request, $loggedInUser);

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
        } catch (AuthenticationException $e) {
            return new JsonResponse([
                'message' => 'User is not authenticated',
                'code' => JsonResponse::HTTP_FORBIDDEN,
                'data' => []
            ]);
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

    private function authenticateUser(Request $request, string $userId): void
    {
        $token = $request->headers->get('X-AUTH-TOKEN', '');

        if (!$this->authenticationService->isUserAuthenticated($userId, $token)) {
            throw new AuthenticationException();
        }
    }
}
