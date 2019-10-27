<?php

namespace App\Controller;

use App\Entity\OrderSource;
use App\Form\Type\ReportSalesType;
use App\Form\Value\ReportSalesValue;
use App\Service\ProjectService;
use App\Service\ReportService;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReportsController extends AbstractController
{
    /**
     * @var ReportService
     */
    private $reportService;

    /**
     * @var ProjectService
     */
    private $projectService;

    public function __construct(ReportService $reportService, ProjectService $projectService)
    {
        $this->reportService = $reportService;
        $this->projectService = $projectService;
    }

    /**
     * @Route("/admin/reports/sales", name="reports_sales")
     * @param Request $request
     *
     * @return Response
     * @throws \Doctrine\DBAL\DBALException
     */
    public function sales(Request $request)
    {
        $projects = $this->projectService->getProjectsByUser($this->getUser());
        $currentProject = current($projects);

        $value = new ReportSalesValue();
        $value->setProject($currentProject);

        $form = $this->createForm(
            ReportSalesType::class,
            $value
        );
        $form->handleRequest($request);
        $form->isSubmitted() && $form->isValid();

        $result = $this->reportService->orderSales($form->getData());
        $result->setVisibleColumns(
            [
                'source',
                'medium',
                'advert_cost',
                'advert_views',
                'advert_clicks',
                'order_count',
                'order_convert',
                'cpl',
                'order_complete_count',
                'order_complete_sum',
                'order_complete_profit',
                'cpo',
                'profit',
            ]
        );

        return $this->render(
            'admin/reports/sales.html.twig',
            [
                'report' => $result,
                'filter' => $form->createView(),
            ]
        );
    }
}