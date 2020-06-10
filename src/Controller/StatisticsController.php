<?php

namespace App\Controller;

use App\Entity\Groups;
use App\Entity\Test;
use App\Entity\User;
use App\Service\StatsService;
use App\Service\UserService;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StatisticsController extends AbstractController
{
    /**
     * @Route("/teacher/stats/groups", name="admin_stats_between")
     */
    public function statsAllGroups(StatsService $service)
    {
        $groups = $this->getDoctrine()->getRepository(Groups::class)->findAll();

        $resArray = array();

        $header = array();
        $header[] = 'Группа';
        $header[] = 'Ср.балл';

        $resArray[] = $header;

        foreach ($groups as $item) {
            $array = array();
            $array[] = $item->getName();
            $array[] = $service->getAvgValueInGroupByTests($item);
            $resArray[] = $array;
        }

        $chart = new ColumnChart();

        $chart->getData()->setArrayToDataTable(
            $resArray
        );

        $chart->getOptions()->setTitle('Средний балл за тесты по группам');
        $chart->getOptions()->setHeight(500);
        $chart->getOptions()->setWidth(900);
        $chart->getOptions()->setColors(['#ffc800']);
        $chart->getOptions()->getTitleTextStyle()->setBold(true);
        $chart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $chart->getOptions()->getTitleTextStyle()->setItalic(true);
        $chart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $chart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('statistics/between-groups.html.twig', [
            'controller_name' => 'StatisticsController',
            'chart' => $chart,
            'groups' => $groups,
        ]);
    }

    /**
     * @Route("/teacher/stats/groups/{id}", name="admin_stats_group", requirements={"id"="\d+"})
     */
    public function groupStats(StatsService $service, int $id, PaginatorInterface $paginator, Request $request)
    {
        $group = $this->getDoctrine()->getRepository(Groups::class)->find($id);

        $tests = $this->getDoctrine()->getRepository(Test::class)->findAll();

        $studentsQuery = $this->getDoctrine()->getRepository(User::class)
            ->createQueryBuilder('s')
            ->select('s')
            ->where('s.groups = :group')
            ->orderby('s.fullName', 'ASC')
            ->setParameter('group', $group)
            ->getQuery();

        $students = $paginator->paginate(
            $studentsQuery,
            $request->query->getInt('page', 1),
            100
        );


        $header = array();
        $header[] = 'Тест';
        $header[] = 'Ср.балл';

        $resArray[] = $header;

        foreach ($tests as $test) {
            $array = array();
            $array[] = $test->getName();
            $array[] = $service->getAvgValueByTest($group, $test);
            $resArray[] = $array;
        }

        $chart = new ColumnChart();

        $chart->getData()->setArrayToDataTable(
            $resArray
        );

        $chart->getOptions()->setTitle('Средний балл за тесты');
        $chart->getOptions()->setHeight(500);
        $chart->getOptions()->setWidth(900);
        $chart->getOptions()->setColors(['#ffc800']);
        $chart->getOptions()->getTitleTextStyle()->setBold(true);
        $chart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $chart->getOptions()->getTitleTextStyle()->setItalic(true);
        $chart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $chart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('statistics/group.html.twig', [
            'controller_name' => 'StatisticsController',
            'chart' => $chart,
            'students' => $students,
            'group' => $group,
        ]);
    }

    /**
     * @Route("/teacher/stats/student/{id}", name="admin_stats_student", requirements={"id"="\d+"})
     * @param int $id
     * @param UserService $userService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function studentStats(int $id, StatsService $service, UserService $userService, PaginatorInterface $paginator, Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

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
        $chart->getOptions()->setColors(['#ffc800']);
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

        return $this->render('statistics/student.html.twig', [
            'controller_name' => 'StatisticsController',
            'chart' => $chart,
            'student' => $user,
            'labStats' => $labStats,
            'testStats' => $testStats,
        ]);
    }
}
