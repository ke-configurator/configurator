<?php

namespace App\Repository;

use App\Entity\SpreadSheet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpreadSheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpreadSheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpreadSheet[]    findAll()
 * @method SpreadSheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpreadSheetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpreadSheet::class);
    }

    // /**
    //  * @return SpreadSheet[] Returns an array of SpreadSheet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SpreadSheet
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
