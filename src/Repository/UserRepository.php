<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

        public function findOneByUsername($value): ?User
        {
            return $this->createQueryBuilder('u')
                ->andWhere('u.username = :val')
                ->setParameter('val', $value)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }

        public function findOneByUsernamePassword($username, $password): ?User
        {
            return $this->createQueryBuilder('u')
                ->andWhere('u.username = :uname')
                ->andWhere('u.password = :pass')
                ->setParameter('uname', $username)
                ->setParameter('pass', $password)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }
}
