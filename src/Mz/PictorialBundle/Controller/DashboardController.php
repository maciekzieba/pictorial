<?php

namespace Mz\PictorialBundle\Controller;

use Mz\PictorialBundle\Service\ReportService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\DiExtraBundle\Annotation as DI;

class DashboardController extends Controller
{
    /**
     * @DI\Inject("pictorial.report")
     * @var ReportService
     */
    protected $reportService;


    /**
     * @Route("/", name="dashboard")
     * @Template()
     */
    public function indexAction()
    {
        $visitsByRealizationStat = $this->reportService->getVisitsByRealizationStat();
        $visitsByRealizationStatChart = array();
        foreach ($visitsByRealizationStat as $row) {
            $visitsByRealizationStatChart[] = array('label' => $row['name'], 'value' => intval($row['amount']));
        }

        $visitsByContactSourceStat = $this->reportService->getVisitsByContactSourceStat();
        $visitsByContactSourceStatChart = array();
        foreach ($visitsByContactSourceStat as $row) {
            $visitsByContactSourceStatChart[] = array('label' => $row['name'], 'value' => intval($row['amount']));
        }

        return array(
            'packagesCount' => $this->reportService->getPackagesCount(),
            'visitsCount' => $this->reportService->getVisitsCount(),
            'packagesValueNet' => $this->reportService->getPackagesValueNet(),
            'packagesToInvoicedCount' => $this->reportService->getPackagesToInvoicedCount(),
            'packagesToDelayedCount' => $this->reportService->getPackagesToDelayedCount(),
            'visitsByRealizationStat' => $visitsByRealizationStat,
            'visitsByRealizationStatChart' => json_encode($visitsByRealizationStatChart),
            'visitsByCityStat' => $this->reportService->getVisitsByCityStat(),
            'visitsByContactSourceStat' => $visitsByContactSourceStat,
            'visitsByContactSourceStatChart' => json_encode($visitsByContactSourceStatChart),
        );
    }
}
