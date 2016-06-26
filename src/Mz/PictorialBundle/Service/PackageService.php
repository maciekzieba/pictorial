<?php

namespace Mz\PictorialBundle\Service;

use Doctrine\ORM\EntityManager;
use Mz\PictorialBundle\Entity\Package;
use Mz\PictorialBundle\Entity\User;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;


/**
 * @Service("pictorial.package")
 */
class PackageService
{
    /**
     * @var EntityManager
     */
    private $em;

    protected $statuses = array(
        'commissioned' => 'Zlecony',
        'passed' => 'Zdany',
        'invoiced' => 'Zafakturowany',
        'paid' => 'Rozliczony',
        'delayed' => 'Zaległy'
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
    public function getStatuses()
    {
        return $this->statuses;
    }

    /**
     * @param $status
     * @return string
     */
    public function getStatusText($status)
    {
        if (isset($this->statuses[$status])) {
            return $this->statuses[$status];
        }
        return "";
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function demandPackage($id)
    {
        $package = $this->getPackage($id);
        if (!$package instanceof Package) {
            throw new \Exception('Nie odnaleziono pakietu o id "' . $id . '"');
        }

        return $package;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getPackage($id)
    {
        $builder = $this->em->createQueryBuilder();
        $builder->select('p')
            ->from('MzPictorialBundle:Package', 'p')
            ->where('p.id = :id')
            ->setParameter('id', $id);

        return $builder->getQuery()->getOneOrNullResult();
    }


    /**
     * @param Package $package
     * @return Package
     */
    public function savePackage(Package $package)
    {

        $this->em->persist($package);
        $this->em->flush();

        return $package;
    }

    /**
     * @param Package $package
     */
    public function removePackage(Package $package)
    {
        $this->em->remove($package);
        $this->em->flush();
    }
}