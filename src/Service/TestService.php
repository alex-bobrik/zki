<?php


namespace App\Service;


use App\Entity\Question;
use App\Entity\Test;
use App\Entity\TestResult;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Json;

class TestService
{
    private $em;

    private $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function saveQuestion(Question $question)
    {
        $this->em->persist($question);
        $this->em->flush();
    }

    public function saveTest(Test $test)
    {
        $this->em->persist($test);
        $this->em->flush();
    }

    public function startTest(Test $test): TestResult
    {
        $testResult = new TestResult();

        $userName = $this->security->getUser()->getUsername();
        $student = $this->em->getRepository(User::class)
            ->findOneBy(['username' => $userName]);

        $testResult->setStudents($student);
        $testResult->setTests($test);
        $testResult->setCorrectQuestions(0);
        $testResult->setStartDate(new \DateTime('now'));
        $testResult->setCurrentQuestionNumber(0);

        $this->em->persist($testResult);
        $this->em->flush();

        return $testResult;
    }

    public function endTest(TestResult $testResult)
    {
        $result = ($testResult->getCorrectQuestions() * 10) / ($testResult->getTests()->getTestQuestions()->count());

        $date = new \DateTime('now');

        $qb = $this->em->getRepository(TestResult::class)
            ->createQueryBuilder('tr')
            ->update()
            ->set('tr.result', '?1')
            ->set('tr.endDate', '?2')
            ->where('tr.id = :id')
            ->setParameter('id', $testResult->getId())
            ->setParameter(1, $result)
            ->setParameter(2, $date)
            ->getQuery()
            ->execute();
    }

    public function getCurrentQuestion(TestResult $testResult): ?Question
    {
        $questions = $testResult->getTests()->getTestQuestions();

        return $questions[$testResult->getCurrentQuestionNumber()]->getQuestions();
    }

    public function addCorrectQuestion(TestResult $testResult)
    {
//        $testResult->setCorrectQuestions($testResult->getCorrectQuestions() + 1);
//
//        $this->em->persist($testResult);
//        $this->em->flush();

//        $qb = $this->em->getRepository(TestResult::class)
//            ->createQueryBuilder('tr')
//            ->set
//            ->set('tr.correctQuestions', $testResult->getCorrectQuestions() + 1)
//            ->where('tr.id = :id')
//            ->setParameter('id', $testResult->getId())
//            ->getQuery()
//            ->execute();

        $qb = $this->em->getRepository(TestResult::class)
            ->createQueryBuilder('t')
            ->update()
            ->set('t.correctQuestions', $testResult->getCorrectQuestions() + 1)
            ->where('t.id = :id')
            ->setParameter('id', $testResult->getId())
            ->getQuery()
            ->execute();
    }

    public function getQuestionsAmount(TestResult $testResult): int
    {
        return $testResult->getTests()->getTestQuestions()->count();
    }

    public function setNextQuestion(TestResult $testResult)
    {
//        $testResult->setCurrentQuestionNumber($testResult->getCurrentQuestionNumber() + 1);
//
//        $this->em->persist($testResult);
//        $this->em->flush();

        $qb = $this->em->getRepository(TestResult::class)
            ->createQueryBuilder('tr')
            ->update()
            ->set('tr.currentQuestionNumber', $testResult->getCurrentQuestionNumber() + 1)
            ->where('tr.id = :id')
            ->setParameter('id', $testResult->getId())
            ->getQuery()
            ->execute();
    }
    
    public function getCorrectArray(Question $question): array
    {
        $arr = array();

        foreach ($question->getAnswers() as $answer) {
            if ($answer->getIsCorrect())
            {
                $arr[] = $answer->getName();
            }
        }

        return $arr;
    }

}