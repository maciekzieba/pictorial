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

    protected $paymentStatuses = array(
        'realized' => 'W realizacji',
        'invoiced' => 'Zafakturowana',
        'paid' => 'Rozliczona',
        'delayed' => 'Zaległa'
    );

    protected $realizationStatuses = array(
        'ordered' => 'Zlecona',
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
     * @param $field
     * @param $value
     * @throws \Exception
     */
    public function updateVisitField(Visit $visit, $field, $value)
    {
        $newValue = $value;
        switch ($field) {
            case 'cardNumber':
                $visit->setCardNumber($value);
                break;
            case 'firstname':
                $visit->setFirstname($value);
                break;
            case 'lastname':
                $visit->setLastname($value);
                break;
            case 'city':
                $visit->setCity($value);
                break;
            case 'district':
                $visit->setDistrict($value);
                break;
            case 'number':
                if (strlen($value)) {
                    $visit->setNumber($value);
                } else {
                    throw new \Exception('Numer nie może być pusty.');
                }
                break;
            case 'realizationStatus':
                if (isset($this->realizationStatuses[$value])) {
                    $visit->setRealizationStatus($value);
                    $newValue = $this->realizationStatuses[$value];
                } else {
                    throw new \Exception('Niepoprawny status realizacji.');
                }
                break;
            case 'paymentStatus':
                if (isset($this->paymentStatuses[$value])) {
                    $visit->setPaymentStatus($value);
                    $newValue = $this->paymentStatuses[$value];
                } else {
                    throw new \Exception('Niepoprawny status płatności.');
                }
                break;
            default:
                throw new \Exception("Wrong field name.");
                break;
        }
        $this->saveVisit($visit);
        return $newValue;
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