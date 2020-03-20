<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StatisticsController extends AbstractController
{
    /**
     * @Route("/admin/stats/groups", name="admin_stats_between")
     */
    public function index()
    {


        return $this->render('statistics/index.html.twig', [
            'controller_name' => 'StatisticsController',
        ]);
    }

    /**
     * @Route("/admin/stats/group/{id}", name="admin_stats_group")
     */
    public function groupStats()
    {
        return $this->render('statistics/group.html.twig', [
            'controller_name' => 'StatisticsController',
        ]);
    }

    /**
     * @Route("/admin/stats/student/{id}", name="admin_stats_student")
     * @param int $id
     * @param UserService $userService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function studentStats(int $id, UserService $userService)
    {
        $student = $this->getDoctrine()->getRepository(User::class)->find($id);

        $labStats = $userService->getStudentLabsStats($student);
        $testStats = $userService->getStudentTestsStats($student);

        return $this->render('statistics/student.html.twig', [
            'controller_name' => 'StatisticsController',
            'student' => $student,
            'labStats' => $labStats,
            'testStats' => $testStats,
        ]);
    }
}
