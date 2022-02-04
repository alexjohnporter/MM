<?php

declare(strict_types=1);

namespace App\Message;

use Symfony\Component\Uid\Uuid;

class CreateUser implements \JsonSerializable
{
    public function __construct(
        private Uuid $id,
        private string $email,
        private string $password,
        private string $name,
        private string $gender,
        private int $age,
    ) {
    }

    public function getId(): Uuid
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

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'age' => $this->age,
            'gender' => $this->gender
        ];
    }
}
