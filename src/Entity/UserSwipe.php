<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserSwipeRepository;
use DateTime;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserSwipeRepository::class)]
class UserSwipe
{
    public const YES = 1;
    public const NO = 0;

    public const ATTRACTED_VALUES_MAPPING = [
        'yes' => self::YES,
        'no' => self::NO
    ];

    public function __construct(
        #[ORM\Id,
            ORM\Column(type: "string", length: 38, unique: true)]
        private string $id,
        #[ORM\ManyToOne(targetEntity: "App\Entity\User", cascade: ["all"], fetch: "EAGER")]
        #[ORM\JoinColumn(nullable: false)]
        private User $loggedInUser,
        #[ORM\ManyToOne(targetEntity: "App\Entity\User", cascade: ["all"], fetch: "EAGER")]
        #[ORM\JoinColumn(nullable: false)]
        private User $swipedUser,
        #[ORM\Column(type: "integer", length: 1)]
        private int $attracted,
        #[ORM\Column(type: "datetime")]
        private DateTime $swipedAt
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLoggedInUser(): User
    {
        return $this->loggedInUser;
    }

    public function getSwipedUser(): User
    {
        return $this->swipedUser;
    }


    public function isAttracted(): int
    {
        return $this->attracted;
    }

    public function getSwipedAt(): DateTime
    {
        return $this->swipedAt;
    }
}
