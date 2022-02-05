<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UserSwipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserSwipeRepository extends ServiceEntityRepository implements UserSwipeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSwipe::class);
    }

    public function haveUsersPreviouslySwiped(string $loggedInUser, string $swipedUser): bool
    {
        $qb = $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.loggedInUser = :loggedInUser')
            ->andWhere('u.swipedUser = :swipedUser')
            ->setParameters([
                'loggedInUser' => $loggedInUser,
                'swipedUser' => $swipedUser
            ]);

        return (int)$qb->getQuery()->getSingleScalarResult() > 0;
    }

    // normally I'd be consistent with the method I'm using for fetching/persisting entities
    // but I want to display that I know SQL and aren't relying solely on Doctrine
    // also from a performance POV, native queries are much faster than hydrating entire
    // objects
    public function save(
        string $id,
        string $loggedInUser,
        string $swipedUser,
        int $attracted,
        \DateTime $swipedAt
    ): void {
        $this
            ->getEntityManager()
            ->getConnection()->executeQuery(
                'INSERT INTO user_swipe (id, logged_in_user_id, swiped_user_id, attracted, swiped_at)
                VALUES (:id, :loggedInUser, :swipedUser, :attracted, :swipedAt)',
                [
                    'id' => $id,
                    'loggedInUser' => $loggedInUser,
                    'swipedUser' => $swipedUser,
                    'attracted' => $attracted,
                    'swipedAt' => $swipedAt->format('Y-m-d H:i:s')
                ]
            );
    }
}
