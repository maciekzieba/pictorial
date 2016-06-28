<?php

namespace Mz\PictorialBundle\Service;

use Doctrine\ORM\EntityManager;
use Mz\PictorialBundle\Entity\Package;
use Mz\PictorialBundle\Entity\User;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Mz\PictorialBundle\Entity\Visit;


/**
 * @Service("pictorial.visit")
 */
class VisitService
{
    /**
     * @var EntityManager
     */
    private $em;

    protected $defaultScountingShare = 5.00;
    protected $defaultPhotoShare = 50.00;
    protected $defaultInterviewShare = 20.00;
    protected $defaultPostproductionShare = 15.00;
    protected $defaultEditingShare = 5.00;
    protected $defaultProvisionShare = 5.00;

    protected $paymentStatuses = array(
        'realized' => 'W realizacji',
        'invoiced' => 'Zafakturowana',
        'paid' => 'Rozliczona',
        'delayed' => 'Zaległa'
    );

    protected $realizationStatuses = array(
        'appointed' => 'Umówiona',
        'realized' => 'Zrealizowana',
        'passed' => 'Zdana',
        'paid' => 'Opublikowana',
        'cancelled' => 'Anulowana'
    );

    protected $contactSources = array(
        'database' => 'Baza',
        'scouting' => 'Scouting',
        'contest' => 'Konkurs',
        'initiative' => 'Inicjatywa'
    );


    /**
     * @InjectParams({
     *     "em"                     = @Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param Visit $visit
     * @param User $user
     * @return float
     */
    public function calculateUserVisitPayment(Visit $visit, User $user)
    {
        $payment = 0.00;
        if ($visit->getScountingOwner()->getId() == $user->getId()) {
            $payment += $visit->getPriceNetScouting();
        }
        if ($visit->getPhotoOwner()->getId() == $user->getId()) {
            $payment += $visit->getPriceNetPhoto();
        }
        if ($visit->getPostproductionOwner()->getId() == $user->getId()) {
            $payment += $visit->getPriceNetPostproduction();
        }
        if ($visit->getInterviewOwner()->getId() == $user->getId()) {
            $payment += $visit->getPriceNetInterview();
        }
        if ($visit->getEditingOwner()->getId() == $user->getId()) {
            $payment += $visit->getPriceNetEditing();
        }
        if ($visit->getProvisionOwner()->getId() == $user->getId()) {
            $payment += $visit->getPriceNetProvision();
        }
        return $payment;
    }

    /**
     * @return float
     */
    public function getDefaultScountingShare()
    {
        return $this->defaultScountingShare;
    }

    /**
     * @param float $defaultScountingShare
     */
    public function setDefaultScountingShare($defaultScountingShare)
    {
        $this->defaultScountingShare = $defaultScountingShare;
    }

    /**
     * @return float
     */
    public function getDefaultPhotoShare()
    {
        return $this->defaultPhotoShare;
    }

    /**
     * @param float $defaultPhotoShare
     */
    public function setDefaultPhotoShare($defaultPhotoShare)
    {
        $this->defaultPhotoShare = $defaultPhotoShare;
    }

    /**
     * @return float
     */
    public function getDefaultInterviewShare()
    {
        return $this->defaultInterviewShare;
    }

    /**
     * @param float $defaultInterviewShare
     */
    public function setDefaultInterviewShare($defaultInterviewShare)
    {
        $this->defaultInterviewShare = $defaultInterviewShare;
    }

    /**
     * @return float
     */
    public function getDefaultPostproductionShare()
    {
        return $this->defaultPostproductionShare;
    }

    /**
     * @param float $defaultPostproductionShare
     */
    public function setDefaultPostproductionShare($defaultPostproductionShare)
    {
        $this->defaultPostproductionShare = $defaultPostproductionShare;
    }

    /**
     * @return float
     */
    public function getDefaultEditingShare()
    {
        return $this->defaultEditingShare;
    }

    /**
     * @param float $defaultEditingShare
     */
    public function setDefaultEditingShare($defaultEditingShare)
    {
        $this->defaultEditingShare = $defaultEditingShare;
    }

    /**
     * @return float
     */
    public function getDefaultProvisionShare()
    {
        return $this->defaultProvisionShare;
    }

    /**
     * @param float $defaultProvisionShare
     */
    public function setDefaultProvisionShare($defaultProvisionShare)
    {
        $this->defaultProvisionShare = $defaultProvisionShare;
    }



    /**
     * @return array
     */
    public function getContactSources()
    {
        return $this->contactSources;
    }

    /**
     * @param $contactSource
     * @return string
     */
    public function getContactSourcesText($contactSource)
    {
        if (isset($this->contactSources[$contactSource])) {
            return $this->contactSources[$contactSource];
        }
        return "";
    }

    /**
     * @return array
     */
    public function getRealizationStatuses()
    {
        return $this->realizationStatuses;
    }

    /**
     * @param $status
     * @return string
     */
    public function getRealizationStatusesText($status)
    {
        if (isset($this->realizationStatuses[$status])) {
            return $this->realizationStatuses[$status];
        }
        return "";
    }

    /**
     * @return array
     */
    public function getPaymentStatuses()
    {
        return $this->paymentStatuses;
    }

    /**
     * @param $status
     * @return string
     */
    public function getPaymentStatusesText($status)
    {
        if (isset($this->paymentStatuses[$status])) {
            return $this->paymentStatuses[$status];
        }
        return "";
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function demandVisit($id)
    {
        $visit = $this->getVisit($id);
        if (!$visit instanceof Visit) {
            throw new \Exception('Nie odnaleziono wizyty o id "' . $id . '"');
        }

        return $visit;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getVisit($id)
    {
        $builder = $this->em->createQueryBuilder();
        $builder->select('v')
            ->from('MzPictorialBundle:Visit', 'v')
            ->where('v.id = :id')
            ->setParameter('id', $id);

        return $builder->getQuery()->getOneOrNullResult();
    }


    /**
     * @param Visit $visit
     * @return Visit
     */
    public function saveVisit(Visit $visit)
    {

        $this->em->persist($visit);
        $this->em->flush();

        return $visit;
    }

    /**
     * @param Visit $visit
     */
    public function removePackage(Visit $visit)
    {
        $this->em->remove($visit);
        $this->em->flush();
    }
}