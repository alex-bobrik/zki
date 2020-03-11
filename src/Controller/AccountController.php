<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/student/account", name="student_account")
     */
    public function index()
    {
        $userName = $this->getUser()->getUsername();

        $student = $this->getDoctrine()->getRepository(User::class)
            ->findOneBy(['username' => $userName]);

        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
            'student' => $student,
        ]);
    }
}
