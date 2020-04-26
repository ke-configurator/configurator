<?php

namespace App\Repository;

use App\Entity\CalculationGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CalculationGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method CalculationGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method CalculationGroup[]    findAll()
 * @method CalculationGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalculationGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CalculationGroup::class);
    }

    // /**
    //  * @return CalculationGroup[] Returns an array of CalculationGroup objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CalculationGroup
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
