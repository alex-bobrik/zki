<?php

namespace App\Controller;

use App\Entity\Test;
use App\Entity\User;
use App\Form\RegisterFormType;
use App\Form\SearchType;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/teacher/students", name="admin_students")
     */
    public function index(PaginatorInterface $paginator, Request $request, RouterInterface $router)
    {
        $q = $request->get('q');

        if ($q) {
            $usersQuery = $this->getDoctrine()->getRepository(User::class)
                ->createQueryBuilder('u')
                ->select('u')
                ->where('u.fullName like :name')
                ->orderBy('u.fullName', 'ASC')
                ->setParameter('name', '%'.$q.'%')
                ->getQuery();
        } else {
            $usersQuery = $this->getDoctrine()->getRepository(User::class)
                ->createQueryBuilder('u')
                ->select('u')
                ->orderBy('u.fullName', 'ASC')
                ->getQuery();
        }

        $formSearch = $this->createForm(SearchType::class);
        $formSearch->handleRequest($request);
        if ($formSearch->isSubmitted()) {
            $query = $formSearch->get('query')->getData();

            return new RedirectResponse($router->generate('admin_students', ['q' => $query]));
        }

        $users = $paginator->paginate(
            $usersQuery,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users,
            'formSearch' => $formSearch->createView(),
        ]);
    }

    /**
     * @Route("/teacher/students/new", name="admin_students_new")
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
     * @Route("/teacher/students/edit/{id}", name="admin_students_edit")
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

            return $this->redirectToRoute('admin_stats_student', ['id' => $student->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
            'studentId' => $id,
        ]);
    }

    /**
     * @Route("/teacher/students/delete/{id}", name="admin_students_delete")
     * @param int $id
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function deleteStudent(int $id, EntityManagerInterface $em)
    {
        $student = $this->getDoctrine()->getRepository(User::class)->find($id);

        $em->remove($student);
        $em->flush();

        return $this->redirectToRoute('admin_students');
    }
}
