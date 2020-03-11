<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterFormType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/students", name="admin_students")
     */
    public function index()
    {
        $users = $this->getDoctrine()->getRepository(User::class)
            ->findAll();

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/students/{id}", name="admin_students_info")
     * @param int $id
     * @return Response
     */
    public function studentInfo(int $id)
    {
        $student = $this->getDoctrine()->getRepository(User::class)->find($id);

        return $this->render('user/info.html.twig', [
            'controller_name' => 'UserController',
            'student' => $student,
        ]);
    }

    /**
     * @Route("/admin/students/new", name="admin_students_new")
     * @param Request $request
     * @param UserService $userService
     * @return Response
     */
    public function newStudent(Request $request, UserService $userService)
    {
        $student = new User();

        $form = $this->createForm(RegisterFormType::class, $student);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $student = $form->getData();

            $userService->createUser($student);

            return $this->redirectToRoute('admin_students');
        }

        return $this->render('user/new.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/students/edit/{id}", name="admin_students_edit")
     * @param Request $request
     * @param UserService $userService
     * @param int $id
     * @return Response
     */
    public function editStudent(Request $request, UserService $userService, int $id)
    {
        $student = $this->getDoctrine()->getRepository(User::class)->find($id);

        $form = $this->createForm(RegisterFormType::class, $student);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $student = $form->getData();

            $userService->updateUser($student);

            return $this->redirectToRoute('admin_students');
        }

        return $this->render('user/new.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
        ]);
    }
}
