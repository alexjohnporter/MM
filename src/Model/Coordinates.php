<?php

declare(strict_types=1);

namespace App\Model;

class Coordinates implements \JsonSerializable
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

    public function jsonSerialize()
    {
        return [
            'lat' => $this->lat,
            'lon' => $this->lon
        ];
    }
}
