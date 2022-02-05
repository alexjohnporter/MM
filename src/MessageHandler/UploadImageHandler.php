<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\UploadImage;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UploadImageHandler
{
    public const ACCEPTED_MIME_TYPES = [
        'image/png',
        'image/jpg',
        'image/jpeg',
        'image/gif'
    ];

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private string $rootDir
    ) {
    }

    public function __invoke(UploadImage $message): void
    {
        $user = $this->userRepository->getUserById($message->getLoggedInUser());
        $file = $message->getFile();
        $filename = sprintf('%s-profile-photo.png', $user->getId());

        if (!in_array($file->getMimeType(), self::ACCEPTED_MIME_TYPES)) {
            throw new FileException('File must be an image');
        }

        //todo - install php-gd to do the image manipulation

        $file->move($this->rootDir . '/photos', $filename);
        $user->addPhoto($filename);
        $this->userRepository->save($user);
    }
}
