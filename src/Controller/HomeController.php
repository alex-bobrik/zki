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
    public function index()
    {
        return $this->redirectToRoute('razdel');
    }

    /**
     * @Route("/system", name="system")
     */
    public function aboutSystem()
    {
        return $this->render('home/about.html.twig');
    }
}
