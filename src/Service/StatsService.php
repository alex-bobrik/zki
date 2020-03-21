<?php


namespace App\Service;


use App\Entity\Groups;
use App\Entity\Test;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\ResultSetMapping;

class StatsService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getAvgValueInGroupByTests(Groups $group): float
    {
        $dql = 'select avg(tr.correctQuestions * 10 / tr.currentQuestionNumber) as srBall
                from App\Entity\Groups g, App\Entity\User u, App\Entity\TestResult tr
                where tr.students = u.id and u.groups = g.id and g.name = ?1';

        try {
            $result = $this->em->createQuery($dql)
                ->setParameter(1, $group->getName())
                ->getSingleScalarResult();
        } catch (NoResultException $e) {
            return 0;
        }

        if ($result) {
            return $result;
        }

        return 0;
    }

    public function getAvgValueByTest(Groups $group, Test $test): float
    {
        $dql = 'select avg(tr.correctQuestions * 10 / tr.currentQuestionNumber) as srBall
                from App\Entity\Groups g, App\Entity\User u, App\Entity\TestResult tr, App\Entity\Test t
                where g.id = u.groups and u.id = tr.students and tr.tests = t.id and t.id = ?1 and g.id = ?2';

        try {
            $result = $this->em->createQuery($dql)
                ->setParameter(1, $test->getId())
                ->setParameter(2, $group->getId())
                ->getSingleScalarResult();
        } catch (NoResultException $e) {
            return 0;
        }

        if ($result) {
            return $result;
        }

        return 0;
    }

    public function getAvgValueByTestForStudent(Groups $group, Test $test, User $user): float
    {
        $dql = 'select avg(tr.correctQuestions * 10 / tr.currentQuestionNumber) as srBall
                from App\Entity\Groups g, App\Entity\User u, App\Entity\TestResult tr, App\Entity\Test t
                where g.id = u.groups and u.id = tr.students and tr.tests = t.id and t.id = ?1 and g.id = ?2 and u.id = ?3';

        try {
            $result = $this->em->createQuery($dql)
                ->setParameter(1, $test->getId())
                ->setParameter(2, $group->getId())
                ->setParameter(3, $user->getId())
                ->getSingleScalarResult();
        } catch (NoResultException $e) {
            return 0;
        }

        if ($result) {
            return $result;
        }

        return 0;
    }
}