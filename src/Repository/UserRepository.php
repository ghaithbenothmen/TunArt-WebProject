<?php
// src/Repository/UserRepository.php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    // Custom method to search users by nom
    public function searchBynom(string $nom): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.nom LIKE :nom')
            ->setParameter('nom', '%' . $nom . '%')
            ->getQuery()
            ->getResult();
    }

    // Custom method to retrieve users sorted by nom
    public function findAllSortedBynom(): array
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function searchByEmail(string $email): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.email LIKE :email')
            ->setParameter('email', '%' . $email . '%')
            ->getQuery()
            ->getResult();
    }

    public function searchByEmail2(string $email): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }


    

    
    


// Tri Email 
    public function findAllSortedByEmail(): array
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.email', 'ASC')
            ->getQuery()
            ->getResult();
    }









// For Stat


    // Custom method to count all users
    public function countAllUsers(): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }


    // End For Stat


}
