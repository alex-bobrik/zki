<?php

namespace App\Repository;

use App\Entity\TestQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TestQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestQuestion[]    findAll()
 * @method TestQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestQuestion::class);
    }

    // /**
    //  * @return TestQuestion[] Returns an array of TestQuestion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TestQuestion
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
