<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Exception\UserDoesNotExistException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function doesUserExist(string $id): bool
    {
        $qb = $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.id = :id')
            ->setParameter('id', $id);

        return (int)$qb->getQuery()->getSingleScalarResult() > 0;
    }

    public function getUnswipedProfilesForLoggedInUser(string $loggedInUserId): array
    {
        return $this->getEntityManager()->getConnection()->executeQuery(
            "SELECT id, email, name, gender, age FROM user 
                WHERE id NOT IN 
                      (SELECT swiped_user_id
                      FROM user_swipe 
                      WHERE logged_in_user_id = '753080bf-2213-4e2f-bb28-5ba8bba1100c'
                    ) AND NOT id = :loggedInUserId
                   ",
            [
                'loggedInUserId' => $loggedInUserId
            ]
        )->fetchAllAssociative();
    }

    public function isUserAuthenticated(string $userId, string $token): bool
    {
        $user = $this->getUserById($userId);

        return
            $user->getAuthToken() === $token &&
            $user->getAuthTokenExpires() > new \DateTime('now');
    }

    public function getUserById(string $userId): User
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.id = :id')
            ->setParameter('id', $userId)
            ->getQuery();

        $user = $qb->getOneOrNullResult();

        if (!$user instanceof User) {
            throw new UserDoesNotExistException($userId);
        }

        return $user;
    }

    public function getUserByEmail(string $email): User
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery();

        $user = $qb->getOneOrNullResult();

        if (!$user instanceof User) {
            throw new UserDoesNotExistException($email);
        }

        return $user;
    }

    public function save(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }
}
