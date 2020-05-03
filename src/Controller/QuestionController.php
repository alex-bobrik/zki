<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Service\TestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
//    /**
//     * @Route("/teacher/questions", name="admin_questions")
//     */
//    public function index()
//    {
//        $questions = $this->getDoctrine()->getRepository(Question::class)->findAll();
//
//        return $this->render('question/index.html.twig', [
//            'controller_name' => 'QuestionController',
//            'questions' => $questions,
//        ]);
//    }

    /**
     * @Route("/teacher/questions/new", name="admin_questions_new")
     * @param Request $request
     * @param TestService $testService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newQuestion(Request $request, TestService $testService)
    {
        $question = new Question();

        $form = $this->createForm(QuestionType::class, $question);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $question = $form->getData();
            $testService->saveQuestion($question);

            return $this->redirectToRoute('admin_test_new');
        }

        return $this->render('question/new.html.twig', [
            'controller_name' => 'QuestionController',
            'form' => $form->createView(),
        ]);
    }
}
