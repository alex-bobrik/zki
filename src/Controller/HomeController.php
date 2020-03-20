<?php

namespace App\Controller;

use App\Entity\TestResult;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index()
    {
        $testRes = $this->getDoctrine()->getRepository(TestResult::class)->find(74);

        dump($testRes->getCorrectQuestions());
        dump($testRes->getTests()->getTestQuestions()->count());
        dump($testRes->getCorrectQuestions() == $testRes->getTests()->getTestQuestions()->count()); die;

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
