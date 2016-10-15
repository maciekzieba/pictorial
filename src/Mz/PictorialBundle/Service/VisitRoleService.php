<?php

namespace Mz\PictorialBundle\Service;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Mz\PictorialBundle\Entity\VisitRole;


/**
 * @Service("pictorial.visit_role")
 */
class VisitRoleService
{
    /**
     * @var EntityManager
     */
    private $em;


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
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function demandVisitRole($id)
    {
        $visitRole = $this->getVisitRole($id);
        if (!$visitRole instanceof VisitRole) {
            throw new \Exception('Nie odnaleziono rekordu o id "' . $id . '"');
        }

        return $visitRole;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getVisitRole($id)
    {
        $builder = $this->em->createQueryBuilder();
        $builder->select('r')
            ->from('MzPictorialBundle:VisitRole', 'r')
            ->where('r.id = :id')
            ->setParameter('id', $id);

        return $builder->getQuery()->getOneOrNullResult();
    }


    /**
     * @param VisitRole $visitRole
     * @return VisitRole
     */
    public function saveVisitRole(VisitRole $visitRole)
    {

        $this->em->persist($visitRole);
        $this->em->flush();

        return $visitRole;
    }

    /**
     * @param VisitRole $visitRole
     */
    public function removeVisitRole(VisitRole $visitRole)
    {
        $this->em->remove($visitRole);
        $this->em->flush();
    }
}