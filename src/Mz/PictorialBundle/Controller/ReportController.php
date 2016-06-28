<?php

namespace Mz\PictorialBundle\Controller;

use Mz\PictorialBundle\Form\ReportClientFilterForm;
use Mz\PictorialBundle\Model\ReportClientFilter;
use Mz\PictorialBundle\Service\PackageService;
use Mz\PictorialBundle\Service\ReportService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;

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
}
