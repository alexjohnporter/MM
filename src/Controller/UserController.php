<?php

declare(strict_types=1);

namespace App\Controller;

use App\Factory\UserMessageFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;

class UserController extends AbstractController
{
    public function __construct(
        private UserMessageFactoryInterface $userMessageFactory,
        private MessageBusInterface $messageBus
    ) {
    }

    public function hello(): JsonResponse
    {
        return new JsonResponse('hello');
    }

    public function create(): JsonResponse
    {
        $userMessage = $this->userMessageFactory->createMessage();

        $this->messageBus->dispatch($userMessage);

        return new JsonResponse([
            'message' => 'User created successfully',
            'code' => JsonResponse::HTTP_OK,
            'data' => $userMessage->jsonSerialize()
        ]);
    }
}
