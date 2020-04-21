<?php

namespace App\Controller;

use App\Entity\Test;
use App\Entity\User;
use App\Form\PasswordFormType;
use App\Service\StatsService;
use App\Service\UserService;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
use Knp\Component\Pager\PaginatorInterface;
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
    public function index(StatsService $service, UserService $userService, PaginatorInterface $paginator, Request $request)
    {
        $userName = $this->getUser()->getUsername();

        $user = $this->getDoctrine()->getRepository(User::class)
            ->findOneBy(['username' => $userName]);

        if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            return $this->redirectToRoute('admin_students');
        }

        $group = $user->getGroups();

        $tests = $this->getDoctrine()->getRepository(Test::class)->findAll();

        $header = array();
        $header[] = 'Тест';
        $header[] = 'Балл';

        $resArray[] = $header;

        foreach ($tests as $test) {
            $array = array();
            $array[] = $test->getName();
            $array[] = $service->getAvgValueByTestForStudent($group, $test, $user);
            $resArray[] = $array;
        }

        $chart = new ColumnChart();

        $chart->getData()->setArrayToDataTable(
            $resArray
        );

        $chart->getOptions()->setTitle('Баллы за тесты');
        $chart->getOptions()->setHeight(500);
        $chart->getOptions()->setWidth(1000);
        $chart->getOptions()->getTitleTextStyle()->setBold(true);
        $chart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $chart->getOptions()->getTitleTextStyle()->setItalic(true);
        $chart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $chart->getOptions()->getTitleTextStyle()->setFontSize(20);

        $labStats = $userService->getStudentLabsStats($user);
        $testStatsQuery = $userService->getStudentTestsStatsQuery($user);

        $testStats = $paginator->paginate(
            $testStatsQuery,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('account/index.html.twig', [
            'controller_name' => 'StatisticsController',
            'chart' => $chart,
            'student' => $user,
            'labStats' => $labStats,
            'testStats' => $testStats,
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
