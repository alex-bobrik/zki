<?php

namespace App\Repository;

use App\Entity\Razdel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Razdel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Razdel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Razdel[]    findAll()
 * @method Razdel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RazdelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Razdel::class);
    }

    // /**
    //  * @return Razdel[] Returns an array of Razdel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Razdel
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
