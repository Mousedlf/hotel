<?php

namespace App\Repository;

use App\Entity\Chose;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chose>
 *
 * @method Chose|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chose|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chose[]    findAll()
 * @method Chose[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChoseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chose::class);
    }

//    /**
//     * @return Chose[] Returns an array of Chose objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Chose
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
