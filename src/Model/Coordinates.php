<?php

declare(strict_types=1);

namespace App\Model;

class Coordinates
{
    public function __construct(
        private float $lat,
        private float $lon
    ) {
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function getLon(): float
    {
        return $this->lon;
    }
}
