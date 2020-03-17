<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordFormType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="student_account")
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

    /**
     * @Route("/account/password", name="student_account_password-edit")
     * @param Request $request
     * @param UserService $userService
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function editPassword(Request $request, UserService $userService, UserPasswordEncoderInterface $passwordEncoder)
    {
        $userName = $this->getUser()->getUsername();

        $student = $this->getDoctrine()->getRepository(User::class)
            ->findOneBy(['username' => $userName]);

        $form = $this->createForm(PasswordFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $student->setPassword(
                $form->get('password')->getData()
            );

            $student->setPassword($userService->encodePassword($passwordEncoder, $student));

            $userService->updateUser($student);

            return $this->redirectToRoute('student_account');
        }

        return $this->render('account/password.html.twig', [
            'controller_name' => 'AccountController',
            'form' => $form->createView(),
        ]);
    }
}
