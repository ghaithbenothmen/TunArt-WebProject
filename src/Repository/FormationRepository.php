<?php

namespace App\Repository;

use App\Entity\Formation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Formation>
 *
 * @method Formation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formation[]    findAll()
 * @method Formation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }

    public function findByArtiste(User $artiste): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.artiste = :artiste')
            ->setParameter('artiste', $artiste)
            ->getQuery()
            ->getResult();
    }

    public function findAllSortedByRate(): array
{
    return $this->createQueryBuilder('f')
        ->leftJoin('f.ratings', 'r')
        ->groupBy('f.id')
        ->orderBy('AVG(r.ratingValue)', 'DESC')
        ->getQuery()
        ->getResult();
}

public function findLimited($limit): array
{
    return $this->createQueryBuilder('f')
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
}
//    /**
//     * @return Formation[] Returns an array of Formation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Formation
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
