<?php

namespace App\Controller;

use App\Entity\Test;
use App\Entity\TestResult;
use App\Form\QuestionType;
use App\Form\TestType;
use App\Service\TestService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/teacher/test", name="admin_test")
     * @param Request $request
     * @param TestService $testService
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {
        $testsQuery = $this->getDoctrine()->getRepository(Test::class)
            ->createQueryBuilder('t');

        $tests = $paginator->paginate(
            $testsQuery,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'tests' => $tests,
        ]);
    }

    /**
     * @Route("/teacher/test/{id}", name="admin_test_info", requirements={"id"="\d+"})
     * @param Request $request
     * @param TestService $testService
     * @return Response
     */
    public function testInfo(int $id, PaginatorInterface $paginator, Request $request)
    {
        $test = $this->getDoctrine()->getRepository(Test::class)->find($id);

        $testResultsQuery = $this->getDoctrine()->getRepository(TestResult::class)
            ->createQueryBuilder('tr')
            ->select('tr')
            ->where('tr.tests = :test')
            ->setParameter('test', $test)
            ->getQuery();

        $testResults = $paginator->paginate(
            $testResultsQuery,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('test/info.html.twig', [
            'controller_name' => 'TestController',
            'test' => $test,
            'testResults' => $testResults,
        ]);
    }

    /**
     * @Route("/teacher/test/delete/{id}", name="admin_test_delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param TestService $testService
     * @return Response
     */
    public function deleteTest(int $id, EntityManagerInterface $em)
    {
        $test = $this->getDoctrine()->getRepository(Test::class)->find($id);

        $em->remove($test);
        $em->flush();

        return  $this->redirectToRoute('admin_test');
    }

    /**
     * @Route("/student/test", name="student_test")
     * @param Request $request
     * @param TestService $testService
     * @return Response
     */
    public function studentTest(PaginatorInterface $paginator, Request $request)
    {
        $testsQuery = $this->getDoctrine()->getRepository(Test::class)
            ->createQueryBuilder('t');

        $tests = $paginator->paginate(
            $testsQuery,
            $request->query->getInt('page', 1),
            10
        );

        if ($this->getUser()->getRoles()[0] == 'ROLE_ADMIN') {
            return $this->redirectToRoute('admin_test');
        }

        return $this->render('test/student_index.html.twig', [
            'controller_name' => 'TestController',
            'tests' => $tests,
        ]);
    }

    /**
     * @Route("/student/test/{id}/pass", name="student_test_pass")
     * @param TestService $testService
     * @param int $id
     * @return Response
     */
    public function passTest(TestService $testService, int $id)
    {
        $test = $this->getDoctrine()->getRepository(Test::class)
            ->find($id);

        $testRes = $testService->startTest($test);

        $startedTest = $this->getDoctrine()->getRepository(TestResult::class)->find($testRes);

        return $this->redirectToRoute('student_test_passing', ['testResId' => $startedTest->getId()]);
    }

    /**
     * @Route("/student/test/{testResId}", name="student_test_passing", requirements={"testResId"="\d+"})
     * @param TestService $testService
     * @param int $testResId
     * @param Request $request
     * @return Response
     */
    public function passingTest(TestService $testService, int $testResId, Request $request)
    {
        $testRes = $this->getDoctrine()->getRepository(TestResult::class)
            ->find($testResId);

        if ($testRes->getStudents()->getUsername() != $this->getUser()->getUsername()) {
            throw new AccessDeniedException('user is wrong');
        }

        if ($testRes->getEndDate() != null) {
            return $this->redirectToRoute('student_test_result', ['id' => $testRes->getId()]);
        }

        $currentQuestion = $testService->getCurrentQuestion($testRes);

        $currentArray = $testService->getCorrectArray($currentQuestion);

        $form = $this->createForm(QuestionType::class, $currentQuestion);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $studentQuestion = $form->getData();


            $studentArray = $testService->getCorrectArray($studentQuestion);

            if ($currentArray == $studentArray) {
                $testService->addCorrectQuestion($testRes);
            }

            if (!($testService->getQuestionsAmount($testRes) === $testRes->getCurrentQuestionNumber() + 1)) {
                $testService->setNextQuestion($testRes);
            } else {
                $testService->setNextQuestion($testRes);
                $testService->endTest($testRes);
            }

            return new JsonResponse('');
        }

        return $this->render('test/passing.html.twig', [
            'controller_name' => 'TestController',
            'form' => $form->createView(),
            'testRes' => $testRes,
        ]);
    }

    /**
     * @Route("/teacher/test/new", name="admin_test_new")
     * @param Request $request
     * @param TestService $testService
     * @return Response
     */
    public function newTest(Request $request, TestService $testService)
    {
        $test = new Test();

        $form = $this->createForm(TestType::class, $test);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $test = $form->getData();
            $testService->saveTest($test);

            return $this->redirectToRoute('admin_test');
        }

        return $this->render('test/new.html.twig', [
            'controller_name' => 'TestController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/student/test/result/{id}", name="student_test_result")
     * @param int $id
     * @return Response
     */
    public function testResult(int $id)
    {
        $testResult = $this->getDoctrine()->getRepository(TestResult::class)->find($id);

        return $this->render('test/result.html.twig', [
            'controller_name' => 'TestController',
            'testResult' => $testResult,
        ]);
    }
}
