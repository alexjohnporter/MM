<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    public const MALE = 'm';
    public const FEMALE = 'f';
    public const PREFER_NOT_TO_SAY = 'o';

    public const GENDERS = [
        'Male' => self::MALE,
        'Female' => self::FEMALE,
        'Prefer not to say' => self::PREFER_NOT_TO_SAY
    ];

    public function __construct(
        #[ORM\Id,
    ORM\Column(type: "uuid", unique: true)]
        private Uuid $id,
        #[ORM\Column(type: 'string', length: 180, unique: true)]
        private string $email,
        #[ORM\Column(type: 'string')]
        private string $password,
        #[ORM\Column(type: 'string')]
        private string $name,
        #[ORM\Column(type: 'string', length: 1)]
        private string $gender,
        #[ORM\Column(type: 'integer')]
        private int $age,
    ) {
        if (!in_array($this->gender, self::GENDERS)) {
            throw new \DomainException(sprintf('Gender with key of %s is not in list', $this->gender));
        }
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
}
