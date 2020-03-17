<?php

namespace App\Repository;

use App\Entity\Lection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Lection|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lection|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lection[]    findAll()
 * @method Lection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lection::class);
    }

    // /**
    //  * @return Lection[] Returns an array of Lection objects
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
    public function findOneBySomeField($value): ?Lection
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
