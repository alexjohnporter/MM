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

    public function getUnswipedProfilesForLoggedInUser(
        string $loggedInUserId,
        int $minAge,
        int $maxAge,
        string | null $gender = null
    ): array {
        $sql = "SELECT id, email, name, gender, age FROM user 
                WHERE id NOT IN 
                      (SELECT swiped_user_id
                      FROM user_swipe 
                      WHERE logged_in_user_id = :loggedInUserId
                    ) AND NOT id = :loggedInUserId
                    AND age > :minAge
                    AND age < :maxAge";

        if ($gender) {
            $sql .= ' AND gender = :gender';
        }

        return $this->getEntityManager()->getConnection()->executeQuery(
            $sql,
            [
                'loggedInUserId' => $loggedInUserId,
                'minAge' => $minAge,
                'maxAge' => $maxAge,
                'gender' => $gender
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
