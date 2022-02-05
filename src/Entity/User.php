<?php

declare(strict_types=1);

namespace App\Entity;

use App\Model\Coordinates;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements \JsonSerializable
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
    ORM\Column(type: "string", length: 38, unique: true)]
        private string $id,
        #[ORM\Column(type: 'string', length: 180, unique: true)]
        private string $email,
        #[ORM\Column(type: 'string')]
        private string $password,
        #[ORM\Column(type: 'string')]
        private string $name,
        #[ORM\Column(type: 'string', length: 1)] // I could use an ENUM here but performance wise, it's slower
        private string $gender,
        #[ORM\Column(type: 'integer')]
        private int $age,
        #[ORM\Column(type: 'float')]
        private float $lat,
        #[ORM\Column(type: 'float')]
        private float $lon,
        #[ORM\Column(type: 'string', nullable: true)]
        private string | null $authToken = null,
        #[ORM\Column(type: 'datetime', nullable: true)]
        private \DateTime | null $authTokenExpires = null,
        #[ORM\Column(type: 'string', nullable: true)]
        private string | null $profilePhoto = null
    ) {
        if (!in_array($this->gender, self::GENDERS)) {
            throw new \DomainException(sprintf('Gender with key of %s is not in list', $this->gender));
        }
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
        return new Coordinates(
            $this->lat,
            $this->lon
        );
    }

    public function getAuthToken(): string | null
    {
        return $this->authToken;
    }

    public function getAuthTokenExpires(): \DateTime | null
    {
        return $this->authTokenExpires;
    }

    public function authenticateUser(string $authToken): void
    {
        $this->authToken = $authToken;
        $this->authTokenExpires = (new \DateTime('now'))->add(new \DateInterval('PT10M'));
    }

    public function getProfilePhoto(): string | null
    {
        return $this->profilePhoto;
    }

    public function addPhoto(string $photo): void
    {
        $this->profilePhoto = $photo;
    }

    public function jsonSerialize()
    {
        return [
           'id' => $this->id,
           'name' => $this->name,
           'email' => $this->email,
           'age' => $this->age,
           'gender' => $this->gender,
            'profilePhoto' => $this->profilePhoto
        ];
    }
}
