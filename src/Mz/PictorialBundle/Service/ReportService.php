<?php

namespace Mz\PictorialBundle\Service;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;


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

    /**
     * @InjectParams({
     *     "em"                     = @Inject("doctrine.orm.entity_manager"),
     *     "visitService"           = @Inject("pictorial.visit")
     * })
     */
    public function __construct(EntityManager $em, VisitService $visitService)
    {
        $this->em = $em;
        $this->visitService = $visitService;
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