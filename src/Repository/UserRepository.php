<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param $value
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function findOneByName($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.username = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param $value
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function findOneByID($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param $role
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function findOneByRole($role): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.role = :val')
            ->setParameter('val', $role)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param $mail
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function findOneByEmail($mail): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.mail = :val')
            ->setParameter('val', $mail)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param $token
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function findOneByToken($token): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.token = :val')
            ->setParameter('val', $token)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
