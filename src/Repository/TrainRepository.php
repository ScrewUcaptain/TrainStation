<?php

namespace App\Repository;

use App\Entity\Train;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Train>
 *
 * @method Train|null find($id, $lockMode = null, $lockVersion = null)
 * @method Train|null findOneBy(array $criteria, array $orderBy = null)
 * @method Train[]    findAll()
 * @method Train[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Train::class);
    }

//    /**
//     * @return Train[] Returns an array of Train objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Train
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
