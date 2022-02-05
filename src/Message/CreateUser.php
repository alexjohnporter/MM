<?php

declare(strict_types=1);

namespace App\Message;

use App\Model\Coordinates;

class CreateUser implements \JsonSerializable
{
    public function __construct(
        private string $id,
        private string $email,
        private string $password,
        private string $name,
        private string $gender,
        private int $age,
        private Coordinates $coordinates
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getCoordinates(): Coordinates
    {
        return $this->coordinates;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'age' => $this->age,
            'gender' => $this->gender,
            'coordinates' => $this->coordinates->jsonSerialize()
        ];
    }
}
