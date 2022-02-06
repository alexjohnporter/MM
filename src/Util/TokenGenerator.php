<?php

declare(strict_types=1);

namespace App\Util;

class TokenGenerator implements TokenGeneratorInterface
{
    public function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}
