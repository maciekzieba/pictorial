<?php

namespace Mz\PictorialBundle\Service;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Mz\PictorialBundle\Entity\Package;
use Mz\PictorialBundle\Entity\User;
use Mz\PictorialBundle\Entity\Visit;
use Mz\PictorialBundle\Model\ReportClientFilter;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Kernel;


/**
 * @Service("pictorial.report")
 */
class ReportService
{
    /**
     * @var EntityManager
     */
    private $em;

    /** @var VisitService */
    private $visitService;

    /** @var  \Liuggio\ExcelBundle\Factory */
    protected $excel;

    /** @var Kernel */
    protected $kernel;

    /**
     * @InjectParams({
     *     "em"                     = @Inject("doctrine.orm.entity_manager"),
     *     "visitService"           = @Inject("pictorial.visit"),
     *     "excel"                  = @Inject("phpexcel"),
     *     "kernel"                 = @Inject("kernel")
     * })
     */
    public function __construct(EntityManager $em, VisitService $visitService, \Liuggio\ExcelBundle\Factory $excel, Kernel $kernel)
    {
        $this->em = $em;
        $this->visitService = $visitService;
        $this->excel = $excel;
        $this->kernel = $kernel;
    }

    /**
     * @param ReportClientFilter $filters
     * @return array
     */
    public function getVisitsToClientReport(ReportClientFilter $filters)
    {
        $builder = $this->em->createQueryBuilder();
        $builder->select('v, p, o')
            ->from('MzPictorialBundle:Visit', 'v')
            ->innerJoin('v.package', 'p')
            ->leftJoin('v.photoOwner', 'o')
            ->orderBy('v.number', 'ASC')
            ->where('1=1');
        if ($filters->getPackage() instanceof Package) {
            $builder->andWhere('v.package = :package')->setParameter('package', $filters->getPackage()->getId());
        }
        if ($filters->getPackageDateFrom() instanceof \DateTime) {
            $builder->andWhere('p.validityDate >= :dateFrom')->setParameter('dateFrom', $filters->getPackageDateFrom());
        }
        if ($filters->getPackageDateTo() instanceof \DateTime) {
            $builder->andWhere('p.validityDate <= :dateTo')->setParameter('dateTo', $filters->getPackageDateTo());
        }

        return $builder->getQuery()->getResult();
    }

    /**
     * @param ReportClientFilter $filters
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \PHPExcel_Exception
     */
    public function renderClientReport(ReportClientFilter $filters)
    {
        $phpExcel = $this->excel->createPHPExcelObject($this->kernel->locateResource('@MzPictorialBundle/Resources/templates/clientReportTemplate.xlsx'));
        $phpExcel->setActiveSheetIndex(0);
        $activesheet = $phpExcel->getActiveSheet();

        $activesheet->setCellValue('C2',date("d.m.Y"));

        $startingPosition = 4;
        $data = $this->getVisitsToClientReport($filters);
        $rowCursor = $startingPosition;
        /** @var Visit $visit */
        foreach ($data as $visit) {
            if ($rowCursor > $startingPosition) {
                $activesheet->duplicateStyle($activesheet->getStyle("B$startingPosition"), "A$rowCursor:L$rowCursor");
                $activesheet->duplicateStyle($activesheet->getStyle("C$startingPosition"), "C$rowCursor:C$rowCursor");
            }
            $activesheet->setCellValue('A'.$rowCursor, $visit->getNumber()."/".$visit->getPackage()->getId());
            $activesheet->setCellValue('B'.$rowCursor, $visit->getLpId());
            $activesheet->setCellValue('C'.$rowCursor, $visit->getFirstname()." ".$visit->getLastname());
            $activesheet->setCellValue('D'.$rowCursor, $visit->getDescription());
            $activesheet->setCellValue('E'.$rowCursor, $visit->getCity());
            $activesheet->setCellValue('F'.$rowCursor, $visit->getDistrict());
            $activesheet->setCellValue('G'.$rowCursor, '');
            $activesheet->setCellValue('H'.$rowCursor, $this->visitService->getContactSourcesText($visit->getContactSource()));
            $activesheet->setCellValue('I'.$rowCursor, $visit->getCardNumber());
            if ($visit->getPhotoOwner() instanceof User) {
                $activesheet->setCellValue('J'.$rowCursor, $visit->getPhotoOwner()->getFullName());
            }
            $activesheet->setCellValue('K'.$rowCursor, $this->visitService->getRealizationStatusesText($visit->getRealizationStatus()));
            switch ($visit->getRealizationStatus()) {
                case 'realized':
                    $activesheet->getStyle('K'.$rowCursor)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => '9CBA5F')
                            )
                        )
                    );
                    break;
                case 'passed':
                    $activesheet->getStyle('K'.$rowCursor)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => '0F7F12')
                            )
                        )
                    );
                    break;
                case 'paid':
                    $activesheet->getStyle('K'.$rowCursor)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'FD7E25')
                            )
                        )
                    );
                    break;
                case 'cancelled':
                    $activesheet->getStyle('K'.$rowCursor)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'B70711')
                            )
                        )
                    );
                    break;
            }
            $activesheet->setCellValue('L'.$rowCursor, $visit->getDistrict());
            $rowCursor++;
        }


        $fileName = 'Pictorial raport z wizyt '.date("d_m_Y").'.xlsx';
        $writer = $this->excel->createWriter($phpExcel, 'Excel2007');
        $response = $this->excel->createStreamedResponse($writer);
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $fileName
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }


    /**
     * @return array
     */
    public function getVisitsByRealizationStat()
    {
        $builder = $this->em->createQueryBuilder();
        $builder->select('v.realizationStatus, COUNT(v) AS amount')
            ->from('MzPictorialBundle:Visit', 'v', 'v.realizationStatus')
            ->groupBy('v.realizationStatus');
        $result = $builder->getQuery()->getArrayResult();
        $stat = array();
        foreach ($this->visitService->getRealizationStatuses() as $statusKey => $statusText) {
            $stat[$statusKey] = array(
                'amount' => 0,
                'name' => $statusText
            );
            if (isset($result[$statusKey])) {
                $stat[$statusKey]['amount'] = $result[$statusKey]['amount'];
            }
        }
        return $stat;
    }

    /**
     * @return array
     */
    public function getVisitsByCityStat()
    {
        $builder = $this->em->createQueryBuilder();
        $builder->select('v.city, COUNT(v) AS amount')
            ->from('MzPictorialBundle:Visit', 'v')
            ->groupBy('v.city');
        return $builder->getQuery()->getArrayResult();
    }

    /**
     * @return array
     */
    public function getVisitsByContactSourceStat()
    {
        $builder = $this->em->createQueryBuilder();
        $builder->select('v.contactSource, COUNT(v) AS amount')
            ->from('MzPictorialBundle:Visit', 'v', 'v.contactSource')
            ->groupBy('v.contactSource');
        $result = $builder->getQuery()->getArrayResult();
        $stat = array();
        foreach ($this->visitService->getContactSources() as $key => $text) {
            $stat[$key] = array(
                'amount' => 0,
                'name' => $text
            );
            if (isset($result[$key])) {
                $stat[$key]['amount'] = $result[$key]['amount'];
            }
        }
        return $stat;
    }

    /**
     * @return mixed
     */
    public function getPackagesToDelayedCount()
    {
        $builder = $this->em->createQueryBuilder();
        $builder->select('COUNT(p)')
            ->from('MzPictorialBundle:Package', 'p')
            ->where('p.status LIKE :status')
            ->setParameter('status', 'delayed')
        ;
        return $builder->getQuery()->getSingleScalarResult();
    }

    /**
     * @return mixed
     */
    public function getPackagesToInvoicedCount()
    {
        $builder = $this->em->createQueryBuilder();
        $builder->select('COUNT(p)')
            ->from('MzPictorialBundle:Package', 'p')
            ->where('p.status LIKE :status')
            ->setParameter('status', 'invoiced')
        ;
        return $builder->getQuery()->getSingleScalarResult();
    }

    /**
     * @return mixed
     */
    public function getPackagesCount()
    {
        $builder = $this->em->createQueryBuilder();
        $builder->select('COUNT(p)')
            ->from('MzPictorialBundle:Package', 'p');
        return $builder->getQuery()->getSingleScalarResult();
    }

    /**
     * @return mixed
     */
    public function getPackagesValueNet()
    {
        $builder = $this->em->createQueryBuilder();
        $builder->select('SUM(p.priceNet)')
            ->from('MzPictorialBundle:Package', 'p');
        return $builder->getQuery()->getSingleScalarResult();
    }

    /**
     * @return mixed
     */
    public function getVisitsCount()
    {
        $builder = $this->em->createQueryBuilder();
        $builder->select('COUNT(v)')
            ->from('MzPictorialBundle:Visit', 'v');
        return $builder->getQuery()->getSingleScalarResult();
    }

}