<?php

namespace Mz\PictorialBundle\Controller;

use Mz\PictorialBundle\Form\ReportClientFilterForm;
use Mz\PictorialBundle\Form\ReportSettlementFilterForm;
use Mz\PictorialBundle\Model\ReportClientFilter;
use Mz\PictorialBundle\Model\ReportSettlementFilter;
use Mz\PictorialBundle\Service\PackageService;
use Mz\PictorialBundle\Service\ReportService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ReportController extends Controller
{
    /**
     * @DI\Inject("pictorial.report")
     * @var ReportService
     */
    protected $reportService;

    /**
     * @DI\Inject("pictorial.package")
     * @var PackageService
     */
    protected $packageService;


    /**
     * @Route("/report/client", name="report_client")
     * @Security("has_role('ROLE_CLIENT')")
     * @Template()
     */
    public function clientAction(Request $request)
    {
        $filters = new ReportClientFilter();
        $form = $this->createForm(new ReportClientFilterForm($this->packageService), $filters);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                return $this->reportService->renderClientReport($filters);
            } else {

            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/report/settlement", name="report_settlement")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     */
    public function settlementAction(Request $request)
    {
        $user = $this->getUser();
        $filters = new ReportSettlementFilter();
        $form = $this->createForm(new ReportSettlementFilterForm($user), $filters);
        $form->handleRequest($request);
        $data = array();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $this->reportService->getVisitsToSettlementReport($filters);


            } else {

            }
        }

        return array(
            'form' => $form->createView(),
            'data' => $data
        );
    }

}
