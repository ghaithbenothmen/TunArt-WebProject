<?php

namespace App\Repository;

use App\Entity\OeuvreNotification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OeuvreNotification>
 *
 * @method OeuvreNotification|null find($id, $lockMode = null, $lockVersion = null)
 * @method OeuvreNotification|null findOneBy(array $criteria, array $orderBy = null)
 * @method OeuvreNotification[]    findAll()
 * @method OeuvreNotification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OeuvreNotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OeuvreNotification::class);
    }

//    /**
//     * @return OeuvreNotification[] Returns an array of OeuvreNotification objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OeuvreNotification
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
