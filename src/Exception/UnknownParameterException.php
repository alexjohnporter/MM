<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

class UnknownParameterException extends \Exception
{
    public function __construct(string $paramName, string $paramValue)
    {
        $message = sprintf('Parameter (%s) has an unknown value (%s)', $paramName, $paramValue);

        parent::__construct($message, 0, null);
    }
}
