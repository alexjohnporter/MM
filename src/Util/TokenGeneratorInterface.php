<?php

declare(strict_types=1);

namespace App\Util;

interface TokenGeneratorInterface
{
    public function generateToken(): string;
}
