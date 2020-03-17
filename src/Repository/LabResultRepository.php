<?php

namespace App\Repository;

use App\Entity\LabResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method LabResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method LabResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method LabResult[]    findAll()
 * @method LabResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LabResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LabResult::class);
    }

    // /**
    //  * @return LabResult[] Returns an array of LabResult objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LabResult
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
