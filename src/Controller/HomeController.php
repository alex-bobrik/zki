<?php

namespace App\Controller;

use App\Entity\Groups;
use App\Entity\TestResult;
use App\Service\StatsService;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Histogram;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(StatsService $service)
    {
        return $this->redirectToRoute('razdel');

//        $groups = $this->getDoctrine()->getRepository(Groups::class)->findAll();
//
//        $resArray = array();
//
//
//        $header = array();
//        $header[] = 'Группа';
//        $header[] = 'Ср.балл';
//
//        $resArray[] = $header;
//
//        foreach ($groups as $item) {
//            $array = array();
//            $array[] = $item->getName();
//            $array[] = $service->getAvgValueInGroupByTests($item);
//            $resArray[] = $array;
//        }
//
//        $chart = new ColumnChart();
//
//        $chart->getData()->setArrayToDataTable(
//            $resArray
//        );
//
//        $chart->getOptions()->setTitle('Средний балл за тесты по группам');
//        $chart->getOptions()->setHeight(500);
//        $chart->getOptions()->setWidth(900);
//        $chart->getOptions()->getTitleTextStyle()->setBold(true);
//        $chart->getOptions()->getTitleTextStyle()->setColor('#009900');
//        $chart->getOptions()->getTitleTextStyle()->setItalic(true);
//        $chart->getOptions()->getTitleTextStyle()->setFontName('Arial');
//        $chart->getOptions()->getTitleTextStyle()->setFontSize(20);

//        $group = $this->getDoctrine()->getRepository(Groups::class)->find(1);
//
//        $res = $service->getAvgValueByTests($group);



        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'chart' => $chart,
            'groups' => $groups,
        ]);
    }
}
