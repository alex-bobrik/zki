<?php


namespace App\Service;


use App\Entity\Groups;
use Doctrine\ORM\EntityManagerInterface;

class StatsService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

//    public function getAvgValueByTests(Groups $group): float
//    {
//        $query = $this->em->createQuery('
//            SELECT AVG()
//            ');
//
//    }
}