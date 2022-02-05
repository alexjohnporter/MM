<?php

declare(strict_types=1);

namespace App\Message;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadImage
{
    public function __construct(
        private string $loggedInUser,
        private UploadedFile $file
    ) {
    }

    public function getLoggedInUser(): string
    {
        return $this->loggedInUser;
    }

    public function getFile(): UploadedFile
    {
        return $this->file;
    }
}
