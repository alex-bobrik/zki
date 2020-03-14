<?php

namespace App\Controller;

use App\Entity\Test;
use App\Entity\TestResult;
use App\Form\QuestionType;
use App\Form\TestType;
use App\Service\TestService;
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
     * @Route("/admin/test", name="admin_test")
     * @param Request $request
     * @param TestService $testService
     * @return Response
     */
    public function index()
    {
        $tests = $this->getDoctrine()->getRepository(Test::class)->findAll();

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'tests' => $tests,
        ]);
    }

    /**
     * @Route("/student/test", name="student_test")
     * @param Request $request
     * @param TestService $testService
     * @return Response
     */
    public function studentTest()
    {
        $tests = $this->getDoctrine()->getRepository(Test::class)->findAll();

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
            throw new AccessDeniedException('test is end');
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
     * @Route("/admin/test/new", name="admin_test_new")
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
}
